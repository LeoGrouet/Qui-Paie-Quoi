<?php

namespace App\Command;

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
        // $expense = new Expense(3.6, "Camille", ["Alice", "Charles", "Camille"], "Essence");
        // $this->entityManager->persist($expense);
        // $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();

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

        foreach ($expenses as $expense) {

            $amount = $expense->getAmount();
            $amountByParticipants = $expense->getAmountByParticipant();
            $payer = $expense->getPayer();
            $description = $expense->getDescription();
            $rest = $amount - ($amountByParticipants * count($expense->getParticipants()));
            $participants = $expense->getParticipants();

            foreach ($users as &$user) {
                if ($payer === $user["name"]) {
                    if (in_array($user["name"], $participants)) {
                        $user["balance"] += $amount - $amountByParticipants;
                    } else {
                        $user["balance"] += $amount;
                    }
                } else {
                    if (in_array($user["name"], $participants)) {
                        $user["balance"] -= $amountByParticipants;
                    }
                }
            }

            // Convert the amount from centimes to euros
            // $amountFormatted = $amount / 100;
            // $amountByParticipantsFormatted = $amountByParticipants / 100;
            // $restFormatted = $rest / 100;

            // echo (sprintf(
            //     "%s a payé %s€ (%s€ par participant) (%s) : reste %s",
            //     $payer,
            //     $amountFormatted,
            //     $amountByParticipantsFormatted,
            //     $description,
            //     $restFormatted,
            // ) . PHP_EOL);
        }

        foreach ($users as &$user) {
            // Je recupere le nom et la balance de l'utilisateur qui est en positif
            if ($user["balance"] > 0) {
                $usernamePositif = $user["name"];
                $userbalancePositif = $user["balance"];
            } else {
                $userNeg = [];
                array_push($userNeg, $user["name"], $user["balance"]);
            }
            // Les users en negatif doivent leur somme a celui en positif
        }

        return Command::SUCCESS;
    }
}
