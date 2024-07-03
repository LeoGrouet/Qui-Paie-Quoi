<?php

namespace App\Service;

use App\Entity\Bilan;
use App\Entity\Expense;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use Doctrine\Common\Collections\Collection;

class GroupExpenseBalancer
{
    public function __construct(
        readonly private ExpenseRepository $expenseRepository
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
    private function setExpenses(array $expenses, array $bilans): void
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
     * @param array<Bilan>     $bilans
     */
    private function updateBilan(
        array $bilans,
        int $amount,
        Collection $participants,
        User $payer,
        int $amountByParticipants
    ): void {
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

            $this->updateParticipantOwe($participants, $name, $payerName, $owe, $amountByParticipants, $bilan, $cost, $participation);
        }
    }

    private function updateParticipantOwe(
        Collection $participants,
        string $name,
        string $payerName,
        array $owe,
        int $amountByParticipants,
        Bilan $bilan,
        int $cost,
        int $participation
    ): void {
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

    public function showBalance(int $id): array
    {
        $expenses = $this->expenseRepository->findByGroupId($id);

        $bilans = $this->expenseBalancer($expenses);

        foreach ($bilans as $bilan) {
            $name = $bilan->getName();
            $owe = $bilan->getOwe();

            foreach ($owe as $key => $values) {
                $formatedValue = $values;
                $balances[] = [
                    'userOwe' => $key,
                    'amount' => $formatedValue,
                    'to' => $name,
                ];
            }
        }

        return $balances;
    }
}
