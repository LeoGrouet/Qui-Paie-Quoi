<?php

namespace App\Service;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Repository\UserBalanceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseBalancer
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExpenseRepository $expenseRepository,
        private readonly UserBalanceRepository $userBalanceRepository
    ) {}

    public function apply(Expense $expense): void
    {
        $group = $expense->getGroup();
        $amount = $expense->getAmount();
        $participants = $expense->getParticipants();
        $amountByParticipants = $amount / count($participants);
        $payer = $expense->getPayer();

        $payerUserBalance = $this->userBalanceRepository->getUserBalance($payer, $group);
        $payerUserBalance->addAmount($amount);

        $this->entityManager->persist($payerUserBalance);
        $this->entityManager->flush();

        foreach ($participants as $participant) {
            $userBalance = $this->userBalanceRepository->getUserBalance($participant, $group);
            $userBalance->addDebt($amountByParticipants);
        }

        $this->entityManager->flush();
    }
}
