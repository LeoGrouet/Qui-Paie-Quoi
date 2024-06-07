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
        readonly private EntityManagerInterface $entityManager,
        readonly private ExpenseRepository $expenseRepository,
        readonly private GroupRepository $groupRepository,
        readonly private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    private function newUser(array $names)
    {
        // return array_map(
        //     function (string $name): User {
        //         $nameToLower = strtolower($name);
        //         return new User($name, `$nameToLower@gmail.com`, $nameToLower);
        //     },
        //     $names
        // );
        $test = "test";
        return array_map(
            fn (string $name): User => new User($name, `strtolower($test)@gmail.com`, strtolower($name)),
            $names
        );
    }

    private function createUsers()
    {
        // Crée une fonction de création d'user prénant en parametres le Nom $name du User et determinant $user@gmail.com et tolowerstring($name)
        $usersData = [
            "First Group" => new ArrayCollection([
                $this->newUser(["Alice", "Charles", "Camille"])
            ]),

            "Second Group" => new ArrayCollection([
                new User("Pierre", "pierre@gmail.com", "pierre"),
                new User("David", "david@gmail.com", "david"),
                new User("Emilie", "emilie@gmail.com", "emilie"),
                new User("Florence", "florence@gmail.com", "florence")
            ]),

            "Third Group" => new ArrayCollection([
                new User("George", "george@gmail.com", "george"),
                new User("Helene", "helene@gmail.com", "helene"),
            ]),

            "Fourth Group" => new ArrayCollection([
                new User("Isabelle", "isabelle@gmail.com", "isabelle"),
                new User("Julien", "julien@gmail.com", "julien"),
                new User("Leo", "leo@gmail.com", "leo")
            ])
        ];

        foreach ($usersData as $key => $value) {

            $this->persistAndFlush($value);
            $this->createGroup($value, $key);
        }
    }

    private function createGroup($usersData, string $groupId)
    {
        $group = new Group($groupId, "{$groupId} test", $usersData);
        $this->persistAndFlush($group);
    }

    private function persistAndFlush($data)
    {

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    private function loadFirstScenario(): void
    {
        $usersData = new ArrayCollection([
            new User("Alice", "alice@gmail.com", "alice"),
            new User("Charles", "charles@gmail.com", "charles"),
            new User("Camille", "camille@gmail.com", "camille")
        ]);

        foreach ($usersData as $user) {
            $this->entityManager->persist($user);
        }

        echo 'User of first scenario are loaded in DB' . PHP_EOL;

        $group = new Group("First groupe", "groupe test numero 1", $usersData);
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        echo 'Group of first scenario is loaded in DB' . PHP_EOL;

        $users = $group->getUser();
        foreach ($users as $user) {
            $name = strtolower($user->getName());
            $$name = $user;
        }

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
        echo "Expenses of first scenario are loaded in DB" . PHP_EOL;

        $this->entityManager->flush();
    }

    private function loadSecondScenario(): void
    {

        $usersData = new ArrayCollection([
            new User("Pierre", "pierre@gmail.com", "pierre"),
            new User("David", "david@gmail.com", "david"),
            new User("Emilie", "emilie@gmail.com", "emilie"),
            new User("Florence", "florence@gmail.com", "florence")
        ]);

        foreach ($usersData as $user) {
            $this->entityManager->persist($user);
        }

        echo 'User of second scenario are loaded in DB' . PHP_EOL;

        $group = new Group("Second groupe", "groupe test numero 2", $usersData);
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        echo 'Group of second scenario is loaded in DB' . PHP_EOL;

        $users = $group->getUser();
        foreach ($users as $user) {
            $name = strtolower($user->getName());
            $$name = $user;
        }

        $participantsCollectionOne = new ArrayCollection([$david, $emilie, $florence]);

        $expenses = [
            new Expense(10 * 100, "Taxi", $pierre, $participantsCollectionOne, $group)
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        echo 'Expenses of first scenario are loaded in DB' . PHP_EOL;

        $this->entityManager->flush();
    }

    private function loadThirdScenario(): void
    {
        $usersData = new ArrayCollection([
            new User("George", "george@gmail.com", "george"),
            new User("Helene", "helene@gmail.com", "helene"),
        ]);

        foreach ($usersData as $user) {
            $this->entityManager->persist($user);
        }

        echo 'User of third scenario are loaded in DB' . PHP_EOL;

        $group = new Group("Third groupe", "groupe test numero 3", $usersData);
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        echo 'Group of third scenario is loaded in DB' . PHP_EOL;

        $$users = $group->getUser();
        foreach ($users as $user) {
            $name = strtolower($user->getName());
            $$name = $user;
        }

        $participantsCollectionOne = new ArrayCollection([$george, $helene]);

        $expenses = [
            new Expense(10 * 100, "Petit dèj", $george, $participantsCollectionOne, $group),
            new Expense(15 * 100, "Déjeuner", $helene, new ArrayCollection([$george]), $group),
            new Expense(20 * 100, "Diner", $george, new ArrayCollection([$helene]), $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        echo 'Expenses of third scenario are loaded in DB' . PHP_EOL;

        $this->entityManager->flush();
    }

    private function loadFourthScenario(): void
    {
        $usersData = new ArrayCollection([
            new User("Isabelle", "isabelle@gmail.com", "isabelle"),
            new User("Julien", "julien@gmail.com", "julien"),
            new User("Leo", "leo@gmail.com", "leo")
        ]);

        foreach ($usersData as $user) {
            $this->entityManager->persist($user);
        }

        echo 'User of fourth scenario are loaded in DB' . PHP_EOL;

        $group = new Group("Fourth groupe", "groupe test numero 4", $usersData);
        $this->entityManager->persist($group);

        $this->entityManager->flush();

        echo 'Group of fourth scenario is loaded in DB' . PHP_EOL;

        $users = $group->getUser();
        foreach ($users as $user) {
            $name = strtolower($user->getName());
            $$name = $user;
        }

        $participantsCollection = new ArrayCollection([$isabelle, $julien, $leo]);

        $expenses = [
            new Expense(50 * 100, "Peinture", $isabelle, $participantsCollection, $group),
            new Expense(50 * 100, "Faux gazon", $julien, $participantsCollection, $group),
            new Expense(50 * 100, "Plomb", $leo, $participantsCollection, $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        echo 'Expenses of fourth scenario are loaded in DB' . PHP_EOL;

        $this->entityManager->flush();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createUsers();

        return Command::SUCCESS;
    }
}
