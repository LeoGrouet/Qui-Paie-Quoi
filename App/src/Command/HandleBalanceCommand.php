<?php

namespace App\Command;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $io = new SymfonyStyle($input, $output);
        $scenario = $io->choice(
            'Selectionner le scénario à executer:',
            [
                "First",
                "Second",
                "Third",
                "Fourth"
            ],
            multiSelect: true
        )[0];

        $datas = [];

        switch ($scenario) {
            case 'First':
                array_push(
                    $datas,
                    $expense = new Expense(9 * 100, "Alice", ["Alice", "Charles", "Camille"], "Bouteille  d'eau"),
                    $expense = new Expense(6 * 100, "Charles", ["Charles"], "Sandwich"),
                    $expense = new Expense(12 * 100, "Charles", ["Alice", "Camille"], "Nourriture"),
                    $expense = new Expense(36 * 100, "Camille", ["Alice", "Charles", "Camille"], "Essence")
                );
                break;

            case 'Second':
                array_push(
                    $datas,
                    $expense = new Expense(10 * 100, "Pierre", ["David", "Emilie", "Florence"], "Taxi")
                );
                break;

            case 'Third':
                array_push(
                    $datas,
                    $expense = new Expense(10 * 100, "George", ["George", "Helene"], "Petit dèj"),
                    $expense = new Expense(15 * 100, "Helene", ["George"], "Déjeuner"),
                    $expense = new Expense(20 * 100, "George", ["Helene"], "Diner"),
                );
                break;

            case 'Fourth':
                array_push(
                    $datas,
                    $expense = new Expense(50 * 100, "Isabelle", ["Isabelle", "Julien", "Leo"], "Peinture"),
                    $expense = new Expense(50 * 100, "Julien", ["Isabelle", "Julien", "Leo"], "Faux gazon"),
                    $expense = new Expense(50 * 100, "Leo", ["Isabelle", "Julien", "Leo"], "Plomb"),
                );
                break;
            default:
                echo "Ce scénario n'existe pas. Veuillez indiquer : first ,second, third ou fourth";
                return Command::FAILURE;
        }


        foreach ($datas as $expense) {
            $this->entityManager->persist($expense);
        }
        $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();

        $users = [];

        foreach ($expenses as $expense) {
            $users[$expense->getPayer()] = ["name" => $expense->getPayer(), "cost" => 0, "participation" => 0];
            foreach ($expense->getParticipants() as $participant) {
                $users[$participant] = ["name" => $participant, "cost" => 0, "participation" => 0];
            }
        };

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
        echo "Il reste " . ($rest / 100) . " à répartir";

        foreach ($expenses as $expense) {
            $this->entityManager->remove($expense);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
