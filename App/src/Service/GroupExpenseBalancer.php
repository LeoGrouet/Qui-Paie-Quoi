<?php

namespace App\Service;

use App\Entity\Bilan;
use App\Entity\Expense;
use Error;

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
                : [...$bilans, $expense->getPayer() => new Bilan($expense->getPayer())],
            []
        );

        $this->setExpenses($expenses, $bilans);
        dump($bilans);
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
            $participations = $bilan->getParticipation();
            $owe = $bilan->getOwe();

            if ($payer === $name) {
                $bilan->setCost($bilan->getCost() + $amount);
            }

            if (in_array($name, $participants)) {
                $bilan->setParticipation($participations + $amountByParticipants);
            }

            foreach ($participants as $participant) {
                if ($name !== $payer) {
                    break;
                }
                if ($name !== $participant) {
                    $owe[$participant] = ($owe[$participant] ?? 0) + $amountByParticipants;
                }
            }

            $bilan->setOwe($owe);
        }
    }
}
