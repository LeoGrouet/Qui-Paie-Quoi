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
    // TODO: Créer une fonction pour créer tout les users de tout les scénarios

    private function loadFirstScenario()
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
        echo "Expenses of first scenario are loaded in DB" . PHP_EOL;

        $this->entityManager->flush();
    }

    private function loadSecondScenario()
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

        // Définir dynamiquement les variables en fonction du name de chaque User pour pouvoir foreach OU map
        $pierre = $this->userRepository->getUserByName("Pierre");
        $david = $this->userRepository->getUserByName("David");
        $emilie = $this->userRepository->getUserByName("Emilie");
        $florence = $this->userRepository->getUserByName("Florence");

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

    private function loadThirdScenario()
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

        // Définir dynamiquement les variables en fonction du name de chaque User pour pouvoir foreach OU map
        $george = $this->userRepository->getUserByName("George");
        $helene = $this->userRepository->getUserByName("Helene");

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

    private function loadFourthScenario()
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

        // Définir dynamiquement les variables en fonction du name de chaque User pour pouvoir foreach OU map
        $isabelle = $this->userRepository->getUserByName("Isabelle");
        $julien = $this->userRepository->getUserByName("Julien");
        $leo = $this->userRepository->getUserByName("Leo");

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
        $this->loadFirstScenario();
        $this->loadSecondScenario();
        $this->loadThirdScenario();
        $this->loadFourthScenario();

        return Command::SUCCESS;
    }
}
