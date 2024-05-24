<?php

namespace App\Command;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
        $usersData = [
            new User("Alice", "alice@gmail.com", "alice"),
            new User("Charles", "charles@gmail.com", "charles"),
            new User("Camille", "camille@gmail.com", "camille")
        ];

        foreach ($usersData as $user) {
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        echo 'user';

        $group = new Group("First groupe", "groupe test numero 1");
        $this->entityManager->persist($group);

        echo 'group';

        $alice = $this->userRepository->getUserByName("Alice");
        $charles = $this->userRepository->getUserByName("Charles");
        $camille = $this->userRepository->getUserByName("Camille");

        $collection1 = new ArrayCollection([$alice, $charles, $camille]);
        $collection2 = new ArrayCollection([$charles]);
        $collection3 = new ArrayCollection([$alice, $camille]);

        $expenses = [
            new Expense(9 * 100, "Bouteille d'eau", $alice, $collection1, $group),
            new Expense(6 * 100, "Sandwich", $charles, $collection2, $group),
            new Expense(12 * 100, "Nourriture", $charles, $collection3, $group),
            new Expense(36 * 100, "Essence", $camille, $collection1, $group)
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        echo "expenses";

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
