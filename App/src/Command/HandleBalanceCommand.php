<?php

namespace App\Command;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\UserBalance;
use App\Repository\GroupRepository;
use App\Service\ExpenseBalancer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:handle-balance')]
class HandleBalanceCommand extends Command
{
    public function __construct(
        private readonly GroupRepository $groupRepository,
        private readonly ExpenseBalancer $expenseBalancer,
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

        $groupsName = [];

        foreach ($groupsData as $group) {
            $groupsName[$group->getId()] = $group->getName();
        }

        $name = $io->choice(
            'Selectionner le scénario à executer:',
            $groupsName
        );

        if (!is_string($name)) {
            $io->error('Invalid group name selected.');

            return Command::FAILURE;
        }

        if (!$this->groupRepository->findOneBy(['name' => $name]) instanceof Group || null == $this->groupRepository->findOneBy(['name' => $name])) {
            $io->error('Invalid group name selected.');

            return Command::FAILURE;
        } else {
            $group = $this->groupRepository->findOneBy(['name' => $name]);
        }

        /**
         * @var array <int, Expense> $expenses
         */
        $expenses = $group->getExpenses();

        foreach ($expenses as $expense) {
            $this->expenseBalancer->apply($expense);
        }

        /**
         * @var array <int, UserBalance> $balances
         */
        $balances = $group->getUserBalances();

        foreach ($balances as $balance) {
            $output->writeln($balance);
        }

        return Command::SUCCESS;
    }
}
