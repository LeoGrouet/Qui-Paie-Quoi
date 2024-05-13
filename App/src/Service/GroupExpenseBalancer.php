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
            $names[] = $payer;
            foreach ($participants as $participant) {
                $names[] = $participant;
            }

            $names = array_unique($names);

            $this->updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants, $names);
        }
    }

    private function updateBilan($bilans, $amount, $participants, $payer, $amountByParticipants, $names)
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
                $bilanName = $bilan->getname();
                $otherOwe = $bilans[$participant]->getOwe();

                if ($bilanName !== $payer) {
                    break;
                }

                if ($bilanName != $participant) {
                    if (array_key_exists($participant, $owe)) {
                        $owe[$participant] += $amountByParticipants;
                    } else {
                        $owe[$participant] = $amountByParticipants;
                    }
                }
                $otherOwe[$bilanName] = -$amountByParticipants;
                $bilan->setOwe($otherOwe);
                //Exemple premiere dépense, Alice paye 3 euros pour tous
                // Donc pour bilan -> getOwe , j'initialise setOwe + amount dépense 
                // Donc dans le bilans[$participant] -> getOwe 
                // le owe de la personne owe[$bilanName] -= $amountByParticipant
                // Pour $owe[$participant] , faire $bilan->getOwe[]
            }

            $bilan->setOwe($owe);

            // Alice 
            // owe => Charles, 300 , Camille , 300

            // Charles et Camille doivent 300 à Alice

            // Charles 
            // owe => Alice, 600 , Camille , 600

            // Alice et Camille doivent 600 à Charles

            // Camille 
            // owe => Alice, 1200 , Charles , 1200

            //Alice et Charles doivent 1200 à Camille

            //Donc
            // Alice doit 1200 - 300 à Camille = 900 
            // Alice doit 600 - 300 a Charles = 300

            // Charles doit 300 - 600 à Alice = - 300
            // Charles doit 1200 - 600 à Camille = 600

            // Camille doit 300 - 1200 à Alice = - 900
            // Camille doit 600 - 1200 à Charles = - 600

            // Mais comme Alice doit 300 a Charles, on répercute a Camille, donc Alice devra -900 - 300 à Camille et Charles - 600 + 300
            // Donc Alice doit 1200 a Camille et Charles doit 300 à Camille
        }
    }
}
