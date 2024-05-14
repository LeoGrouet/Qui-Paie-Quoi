<?php

namespace App\Service;

use App\Entity\Bilan;
use App\Entity\Expense;

class GroupExpenseBalancer
{
    public function expenseBalancer($expenses)
    {
        /**
         * @var Bilan[]
         */
        $bilans = array_reduce($expenses, static function (array $bilans, Expense $expense) {
            $payer = $expense->getPayer();
            if (!array_key_exists($payer, $bilans)) {
                return [...$bilans, $payer => new Bilan($payer)];
            }

            return $bilans;
        }, []);

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
            $cost = $bilan->getCost();
            $participations = $bilan->getParticipation();
            $owe = $bilan->getOwe();

            if ($payer === $name) {
                $bilan->setCost($cost + $amount);
            }
            if (in_array($name, $participants)) {
                $bilan->setParticipation($participations + $amountByParticipants);
            }

            foreach ($participants as $participant) {
                if ($name === $payer && $name !== $participant) {
                    if (array_key_exists($participant, $owe)) {
                        $owe[$participant] += $amountByParticipants;
                    }
                    $owe[$participant] = $amountByParticipants;
                }
            }

            $bilan->setOwe($owe);
        }
    }
}
