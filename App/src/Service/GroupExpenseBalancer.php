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

        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $participants = $expense->getParticipants();
            $countParticipants = count($participants);
            $rest = $amount % $countParticipants;
            $amountByParticipants = ($amount - $rest) / $countParticipants;
            $payer = $expense->getPayer();

            foreach ($bilans as $user) {
                $name = $user->getName();
                $cost = $user->getCost();
                $participations = $user->getParticipation();
                if ($payer === $name) {
                    $user->setCost($cost + $amount);
                }
                if (in_array($name, $participants)) {
                    $user->setParticipation($participations + $amountByParticipants);
                }
            }
        }

        return $bilans;
    }

    // Methode private pour le array_reduce
    private function reduce($expenses)
    {
    }

    // Methode private pour le for each expense

    // Methode private pour le bilan
}
