<?php

namespace App\Service;

use App\Entity\Bilan;
use App\Entity\Expense;

class GroupExpenseBalancer
{
    /**
     * @return array<string, Bilan>
     */
    public function expenseBalancer($expenses): array
    {
        /**
         * @var array<string, Bilan>
         */
        $bilans = array_reduce(
            $expenses,
            static fn (array $bilans, Expense $expense) => array_key_exists($expense->getPayer(), $bilans)
                ? $bilans
                : [...$bilans, $expense->getPayer() => new Bilan($expense->getPayer()->getName())],
            []
        );

        $this->setExpenses($expenses, $bilans);
        return $bilans;
    }

    private function setExpenses($expenses, $bilans)
    {
        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $participants = $expense->getParticipants();
            $countParticipants = count($participants);
            $rest = $amount % $countParticipants;
            $amountByParticipants = ($amount - $rest) / $countParticipants;
            $payer = $expense->getPayer();

            $this->updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants);
        }
    }

    private function updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants)
    {
        foreach ($bilans as $bilan) {
            $name = $bilan->getName();
            $participation = $bilan->getParticipation();
            $cost = $bilan->getCost();
            $owe = $bilan->getOwe();

            if ($payer === $name) {
                $bilan->setCost($cost + $amount);
            }

            if (in_array($name, $participants)) {
                $bilan->setParticipation($participation + $amountByParticipants);
            }

            foreach ($participants as $participant) {
                if ($name !== $payer || $name === $participant) {
                    continue;
                }

                if (array_key_exists($participant, $owe)) {
                    $owe[$participant] += $amountByParticipants;
                }

                $owe[$participant] = $amountByParticipants;
            }
            $bilan->setBalance($cost - $participation);
            $bilan->setOwe($owe);
        }
    }
}
