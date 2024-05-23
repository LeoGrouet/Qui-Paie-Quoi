<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:upsertInDB')]
class DatabaseFlushTest extends Command
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
        // Crée un User
        $user = new User("Alice", "alice@gmail.com", "alice");
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        // Crée un group
        $user = new Group();
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        // Crée un depense

        return Command::SUCCESS;
    }
}
