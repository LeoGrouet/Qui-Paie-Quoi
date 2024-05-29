<?php

namespace App\Service;

use App\Controller\API\UserController;
use App\Entity\Bilan;
use App\Entity\Expense;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;

class GroupExpenseBalancer
{
    public function __construct(
        private ExpenseRepository $expenseRepository
    ) {
    }
    /**
     * @return array<string, Bilan>
     */
    public function expenseBalancer(array $expenses): array
    {
        /**
         * @var array<string, Bilan>
         */
        $bilans = array_reduce(
            $expenses,
            static fn (array $bilans, Expense $expense) => array_key_exists($expense->getPayer()->getName(), $bilans)
                ? $bilans
                : [...$bilans, $expense->getPayer()->getName() => new Bilan($expense->getPayer()->getName())],
            []
        );
        $this->setExpenses($expenses, $bilans);

        return $bilans;
    }

    /**
     * @param array<Expense> $expenses
     */
    private function setExpenses(array $expenses, array $bilans)
    {
        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $participants = $expense->getParticipants();
            $countParticipants = $participants->count();
            $rest = $amount % $countParticipants;
            $amountByParticipants = ($amount - $rest) / $countParticipants;
            $payer = $expense->getPayer();

            $this->updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants);
        }
    }

    /**
     * @param Collection<User> $participants
     * @param array<Bilan> $bilans
     */
    private function updateBilan(array $bilans, int $amount, Collection $participants, User $payer, int $amountByParticipants)
    {
        foreach ($bilans as $bilan) {
            $payerName = $payer->getName();
            $name = $bilan->getName();
            $participation = $bilan->getParticipation();
            $cost = $bilan->getCost();
            $owe = $bilan->getOwe();

            if ($payerName === $name) {
                $bilan->setCost($cost + $amount);
            }

            if ($participants->exists(static fn ($key, User $user) => $user->getName() === $name)) {
                $bilan->setParticipation($participation + $amountByParticipants);
            }

            foreach ($participants as $participant) {
                $participantName = $participant->getName();
                if ($name !== $payerName || $name === $participantName) {
                    continue;
                }

                if (array_key_exists($participantName, $owe)) {
                    $owe[$participantName] += $amountByParticipants;
                }

                $owe[$participantName] = $amountByParticipants;
            }
            $bilan->setBalance($cost - $participation);
            $bilan->setOwe($owe);
        }
    }
}
