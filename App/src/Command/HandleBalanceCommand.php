<?php

namespace App\Command;

use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use App\Service\GroupExpenseBalancer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:handle-balance')]
class HandleBalanceCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ExpenseRepository $expenseRepository,
        private readonly GroupRepository $groupRepository,
        private readonly GroupExpenseBalancer $groupExpenseBalancer
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

        $id = $this->groupRepository->findIdByName($name);

        $this->outputBalance($id, $output);

        return Command::SUCCESS;
    }

    protected function outputBalance(int $id, OutputInterface $output)
    {
        $balances = $this->groupExpenseBalancer->showBalance($id);

        foreach ($balances as $balance) {
            $output->writeln($balance).PHP_EOL;
        }
    }
}
