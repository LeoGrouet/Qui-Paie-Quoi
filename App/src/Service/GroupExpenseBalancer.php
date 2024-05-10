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
        $names = [];
        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $participants = $expense->getParticipants();
            $countParticipants = count($participants);
            $rest = $amount % $countParticipants;
            $amountByParticipants = ($amount - $rest) / $countParticipants;
            $payer = $expense->getPayer();
            $names[] = $payer;
            foreach ($participants as $participant) {
                $names[] = $participant;
            }

            $this->updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants);
        }

        $names = array_unique($names);

        foreach ($bilans as $bilan) {
            $owes = [];
            foreach ($names as $name) {
                if ($name != $bilan->getName()) {
                    $owes[$name] = 0;
                }
            }
            $bilan->setOwe($owes);
        }
        dump($bilans);
    }

    private function updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants)
    {
        foreach ($bilans as $bilan) {
            $name = $bilan->getName();
            $cost = $bilan->getCost();
            $owe = $bilan->getOwe();
            $participations = $bilan->getParticipation();
            if ($payer === $name) {
                $bilan->setCost($cost + $amount);
            }
            if (in_array($name, $participants)) {
                $bilan->setParticipation($participations + $amountByParticipants);
            }
        }
    }
}
