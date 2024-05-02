<?php

namespace App\Command;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:handle-balance')]
class HandleBalanceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExpenseRepository $expenseRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Handle balance of the group.')
            ->setHelp('This command allows you to handle the balance of a group...');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // //Scénario 1
        // $expenses = [
        //     $expense = new Expense(9 * 100, "Alice", ["Alice", "Charles", "Camille"], "Bouteille  d'eau"),
        //     $expense = new Expense(6 * 100, "Charles", ["Charles"], "Sandwich"),
        //     $expense = new Expense(12 * 100, "Charles", ["Alice", "Camille"], "Nourriture"),
        //     $expense = new Expense(36 * 100, "Camille", ["Alice", "Charles", "Camille"], "Essence")
        // ];
        // foreach ($expenses as $expense) {
        //     $this->entityManager->persist($expense);
        // }
        // $this->entityManager->flush();

        // // Scénario 2
        // $expense = new Expense(10 * 100, "Pierre", ["David", "Emilie", "Florence"], "Taxi");
        // $this->entityManager->persist($expense);
        // $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();

        //Scénario 1
        $users = [
            [
                "name" => "Alice",
                "balance" => 0,
            ],
            [
                "name" => "Charles",
                "balance" => 0,
            ],
            [
                "name" => "Camille",
                "balance" => 0
            ]
        ];

        // //Scénario 2
        // $users = [
        //     [
        //         "name" => "Pierre",
        //         "balance" => 0,
        //     ],
        //     [
        //         "name" => "David",
        //         "balance" => 0,
        //     ],
        //     [
        //         "name" => "Emilie",
        //         "balance" => 0
        //     ],
        //     [
        //         "name" => "Florence",
        //         "balance" => 0
        //     ],
        // ];

        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $amountByParticipants = $expense->getAmountByParticipant();
            $payer = $expense->getPayer();
            $description = $expense->getDescription();
            $rest = $amount - ($amountByParticipants * count($expense->getParticipants()));
            $participants = $expense->getParticipants();
            $randomNum = rand(1, count($participants));
            $i = 0;

            foreach ($users as &$user) {

                // Si le payer est le user 
                if ($payer === $user["name"]) {
                    if (in_array($user["name"], $participants)) {
                        $user["balance"] += $amount - $amountByParticipants;
                    } else {
                        $user["balance"] += $amount;
                    }
                } else {
                    if ($rest > 0 && $i === $randomNum) {
                        $user["balance"] -= $amountByParticipants + $rest;
                    } else if (in_array($user["name"], $participants)) {
                        $user["balance"] -= $amountByParticipants;
                    }
                }
                $i++;
            }
        }

        $userPos = [];
        $userNeg = [];

        foreach ($users as &$user) {
            if ($user["balance"] > 0) {
                array_push($userPos, $user["name"], $user["balance"]);
            } else {
                array_push($userNeg, [$user["name"], $user["balance"]]);
            }
        }

        foreach ($userNeg as $user) {
            echo $user[0] . " doit " . abs($user[1] / 100) . " euros à " . $userPos[0] . PHP_EOL;
        }

        return Command::SUCCESS;
    }
}
