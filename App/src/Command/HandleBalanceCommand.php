<?php

namespace App\Command;

use App\Entity\Expense;
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
            ]
        );

        $data = match ($scenario) {
            'First' => [
                new Expense(9 * 100, "Alice", ["Alice", "Charles", "Camille"], "Bouteille  d'eau"),
                new Expense(6 * 100, "Charles", ["Charles"], "Sandwich"),
                new Expense(12 * 100, "Charles", ["Alice", "Camille"], "Nourriture"),
                new Expense(36 * 100, "Camille", ["Alice", "Charles", "Camille"], "Essence")
            ],
            'Second' =>
            [
                new Expense(10 * 100, "Pierre", ["David", "Emilie", "Florence"], "Taxi")
            ],
            'Third' =>
            [
                new Expense(10 * 100, "George", ["George", "Helene"], "Petit dèj"),
                new Expense(15 * 100, "Helene", ["George"], "Déjeuner"),
                new Expense(20 * 100, "George", ["Helene"], "Diner"),
            ],
            'Fourth' =>
            [
                new Expense(50 * 100, "Isabelle", ["Isabelle", "Julien", "Leo"], "Peinture"),
                new Expense(50 * 100, "Julien", ["Isabelle", "Julien", "Leo"], "Faux gazon"),
                new Expense(50 * 100, "Leo", ["Isabelle", "Julien", "Leo"], "Plomb"),
            ],
        };

        $this->entityManager->beginTransaction();
        foreach ($data as $expense) {
            $this->entityManager->persist($expense);
        }
        $this->entityManager->flush();

        $this->showBalance($output);

        $this->entityManager->rollback();

        return Command::SUCCESS;
    }

    private function showBalance(OutputInterface $output)
    {

        $expenses = $this->expenseRepository->findAll();

        $bilans = $this->groupExpenseBalancer->expenseBalancer($expenses);

        foreach ($bilans as $bilan) {
            $name = $bilan->getName();
            $owe = $bilan->getOwe();

            foreach ($owe as $key => $values) {
                $formatedValue = $values / 100;
                $output->writeln("{$key} doit {$formatedValue} euros à {$name}");
            }
        }
    }
}
