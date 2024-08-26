<?php

namespace App\Service;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseBalancer
{
    public function __construct(
        readonly private EntityManagerInterface $entityManager
    ) {
    }

    public function apply(Expense $expense): void
    {
        $group = $expense->getGroup();
        $userBalances = $group->getUserBalances();
        $amount = $expense->getAmount();
        $participants = $expense->getParticipants();
        $amountByParticipants = $amount / count($participants);
        $payer = $expense->getPayer();

        $payerUserBalance = $this->getUserBalance($userBalances, $group, $payer);
        $payerUserBalance->addAmount($amount);

        $this->entityManager->persist($payerUserBalance);

        foreach ($participants as $participant) {
            $userBalance = $this->getUserBalance($userBalances, $group, $participant);
            $userBalance->addAmount(-$amountByParticipants);
            $this->entityManager->persist($userBalance);
        }

        $this->entityManager->flush();
    }

    /**
     * @param Collection <int, UserBalance> $userBalances
     */
    private function getUserBalance(Collection $userBalances, Group $group, User $user): UserBalance
    {
        foreach ($userBalances as $userBalance) {
            if ($userBalance->getUser() === $user) {
                return $userBalance;
            }
        }

        $userBalance = new UserBalance($user, $group, 0);
        $userBalances->add($userBalance);

        return $userBalance;
    }
}
