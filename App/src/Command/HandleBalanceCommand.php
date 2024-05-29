<?php

namespace App\Command;

use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
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
        private GroupRepository $groupRepository,
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

        $groupsData = $this->groupRepository->findAll();

        foreach ($groupsData as $group) {
            $groupsName[$group->getId()] = $group->getName();
        }

        $name = $io->choice(
            'Selectionner le scénario à executer:',
            $groupsName
        );

        $id = $this->groupRepository->getIdOfGroupByName($name);

        $this->showBalance($id, $output);

        return Command::SUCCESS;
    }

    private function showBalance(int $id, OutputInterface $output): void
    {
        $expenses = $this->expenseRepository->getExpensesOfGroupById($id);

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
