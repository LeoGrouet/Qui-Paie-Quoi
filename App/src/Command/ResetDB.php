<?php

namespace App\Command;

use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:resetDB')]
class ResetDB extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExpenseRepository $expenseRepository,
        private GroupRepository $groupRepository,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $expenses = $this->expenseRepository->findAll();
        $groups = $this->groupRepository->findAll();
        $this->entityManager->remove($groups);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
