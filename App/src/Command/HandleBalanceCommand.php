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
        //Scénario 1
        $expenses = [
            $expense = new Expense(9 * 100, "Alice", ["Alice", "Charles", "Camille"], "Bouteille  d'eau"),
            $expense = new Expense(6 * 100, "Charles", ["Charles"], "Sandwich"),
            $expense = new Expense(12 * 100, "Charles", ["Alice", "Camille"], "Nourriture"),
            $expense = new Expense(36 * 100, "Camille", ["Alice", "Charles", "Camille"], "Essence")
        ];
        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        $this->entityManager->flush();

        // // Scénario 2
        // $expense = new Expense(10 * 100, "Pierre", ["David", "Emilie", "Florence"], "Taxi");
        // $this->entityManager->persist($expense);
        // $this->entityManager->flush();

        // //Scénario 3
        // $expenses = [
        //     $expense = new Expense(10 * 100, "George", ["George", "Helene"], "Petit dèj"),
        //     $expense = new Expense(15 * 100, "Helene", ["George"], "Déjeuner"),
        //     $expense = new Expense(20 * 100, "George", ["Helene"], "Diner"),
        // ];
        // foreach ($expenses as $expense) {
        //     $this->entityManager->persist($expense);
        // }
        // $this->entityManager->flush();


        // //Scénario 4
        // $expenses = [
        //     $expense = new Expense(50 * 100, "Isabelle", ["Isabelle", "Julien", "Leo"], "Peinture"),
        //     $expense = new Expense(50 * 100, "Julien", ["Isabelle", "Julien", "Leo"], "Faux gazon"),
        //     $expense = new Expense(50 * 100, "Leo", ["Isabelle", "Julien", "Leo"], "Plomb"),
        // ];
        // foreach ($expenses as $expense) {
        //     $this->entityManager->persist($expense);
        // }
        // $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();

        //Scénario 1
        $users = [
            [
                "name" => "Alice",
                "cost" => 0,
                "participation" => 0,
            ],
            [
                "name" => "Charles",
                "cost" => 0,
                "participation" => 0,
            ],
            [
                "name" => "Camille",
                "cost" => 0,
                "participation" => 0,
            ]
        ];

        // //Scénario 2
        // $users = [
        //     [
        //         "name" => "Pierre",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "David",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "Emilie",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "Florence",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        // ];

        // //Scénario 3
        // $users = [
        //     [
        //         "name" => "George",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "Helene",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        // ];

        // //Scénario 4
        // $users = [
        //     [
        //         "name" => "Isabelle",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "Julien",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        //     [
        //         "name" => "Leo",
        //         "cost" => 0,
        //         "participation" => 0,
        //     ],
        // ];

        foreach ($expenses as $expense) {
            $amount = $expense->getAmount();
            $participants = $expense->getParticipants();
            $countParticipants = count($participants);
            $rest = $amount % $countParticipants;
            $amountByParticipants = ($amount - $rest) / $countParticipants;
            $payer = $expense->getPayer();

            foreach ($users as &$user) {
                if ($payer === $user["name"]) {
                    if (in_array($user["name"], $participants)) {
                        $user["participation"] += $amountByParticipants;
                    }
                    $user["cost"] += $amount;
                } elseif (in_array($user["name"], $participants)) {
                    $user["participation"] += $amountByParticipants;
                }
            }

            // if ($rest > 0) {
            //     $users[$randomNum]["participation"] += $rest;
            // }
        }

        foreach ($users as &$user) {
            $balance = $user["cost"] - $user["participation"];
            $user["balance"] = $balance;
        }

        foreach ($users as &$user) {
            if ($user["balance"] < 0) {
                echo $user["name"] . " doit " . $user["balance"] / 100 . " euros" . PHP_EOL;
            } else {
                echo $user["name"] . " ne doit rien . Balance = " . $user["balance"] / 100 . " euros" . PHP_EOL;
            }
        }


        foreach ($expenses as $expense) {
            $this->entityManager->remove($expense);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
