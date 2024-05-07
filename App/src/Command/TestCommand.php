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

#[AsCommand(name: 'app:test')]
class TestCommand extends Command
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

    function cube($n)
    {
        return ($n * $n * $n);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {



        $a = [1, 2, 3, 4, 5];
        $b = array_map([TestCommand::class, 'cube'], $a);
        print_r($b);

        return Command::SUCCESS;
    }
}
