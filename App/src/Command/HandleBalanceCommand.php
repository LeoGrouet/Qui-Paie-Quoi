<?php

namespace App\Command;

use App\Entity\Expense;
use App\Entity\Bilan;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\GroupExpenseBalancer;

#[AsCommand(name: 'app:handle-balance')]
class HandleBalanceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExpenseRepository $expenseRepository,
        private GroupExpenseBalancer $groupExpenseBalancer,
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
                echo "Ce scénario n'existe pas. Veuillez indiquer : first, second, third ou fourth";
                return Command::FAILURE;
        }

        foreach ($datas as $expense) {
            $this->entityManager->persist($expense);
        }
        $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();

        $bilans = $this->groupExpenseBalancer->expenseBalancer($expenses);

        $output->writeln($bilans);

        foreach ($expenses as $expense) {
            $this->entityManager->remove($expense);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
