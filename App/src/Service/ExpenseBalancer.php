<?php

namespace App\Service;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
use App\Repository\UserBalanceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseBalancer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserBalanceRepository $userBalanceRepository
    ) {}

    public function apply(Expense $expense): void
    {
        $group = $expense->getGroup();
        $amount = $expense->getAmount();
        $participants = $expense->getParticipants();
        $amountByParticipants = $amount / count($participants);
        $payer = $expense->getPayer();
        $countOfParticipant = count($expense->getParticipants());
        $rest = $amount % $countOfParticipant;

        $payerUserBalance = $this->userBalanceRepository->getUserBalance($payer, $group);

        if (null === $payerUserBalance) {
            $payerUserBalance = new UserBalance($payer, $group);
            $this->entityManager->persist($payerUserBalance);
            $this->entityManager->flush();
        }

        foreach ($participants as $participant) {
            $participantUserBalance = $this->userBalanceRepository->getUserBalance($participant, $group);

            if (null === $participantUserBalance) {
                $participantUserBalance = new UserBalance($participant, $group);
                $this->entityManager->persist($participantUserBalance);
                $this->entityManager->flush();
            }
        }

        if (0 !== $rest) {
            $this->handleRest($expense);

            return;
        }

        $this->updatePayerBalance($group, $amount, $payer);

        foreach ($participants as $participant) {
            $this->updateParticipantBalance($group, $amountByParticipants, $participant);
        }
    }

    private function updatePayerBalance(Group $group, int $amount, User $payer): void
    {
        /**
         * @var UserBalance $payerUserBalance
         */
        $payerUserBalance = $this->userBalanceRepository->getUserBalance($payer, $group);
        $payerUserBalance->addAmount($amount);
    }

    private function updateParticipantBalance(Group $group, int $amount, User $participant): void
    {
        /**
         * @var UserBalance $participantUserBalance
         */
        $participantUserBalance = $this->userBalanceRepository->getUserBalance($participant, $group);
        $participantUserBalance->addDebt($amount);
    }

    private function handleRest(Expense $expense): void
    {
        $group = $expense->getGroup();
        $amount = $expense->getAmount();
        $participants = $expense->getParticipants();
        $payer = $expense->getPayer();
        $countOfParticipant = count($expense->getParticipants());
        $rest = $amount % $countOfParticipant;
        $amountByParticipants = ($amount - $rest) / count($participants);

        $this->updatePayerBalance($group, $amount, $payer);

        foreach ($participants as $participant) {
            $this->updateParticipantBalance($group, $amountByParticipants, $participant);
        }

        $randomNumber = rand(0, $countOfParticipant - 1);

        /**
         * @var User $participant
         */
        $participant = $participants->toArray()[$randomNumber];

        $this->updateParticipantBalance($group, $rest, $participant);

        $this->entityManager->flush();
    }
}
