<?php

namespace App\Command;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:insertInDB')]
class DatabaseFlushTest extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    private function loadFirstScenario(): void
    {
        $usersData = new ArrayCollection([
            $alice = new User('Alice', 'alice@gmail.com', 'password'),
            $charles = new User('Charles', 'charles@gmail.com', 'password'),
            $camille = new User('Camille', 'camille@gmail.com', 'password'),
        ]);

        $this->entityManager->persist($alice);
        $this->entityManager->persist($charles);
        $this->entityManager->persist($camille);

        $this->logger->info('User of first scenario are loaded in DB');

        $group = new Group('First groupe', 'groupe test numero 1', $usersData);

        $this->entityManager->persist($group);

        $this->logger->info('Group of first scenario is loaded in DB');

        $collection1 = new ArrayCollection([$alice, $charles, $camille]);
        $collection2 = new ArrayCollection([$charles]);
        $collection3 = new ArrayCollection([$alice, $camille]);

        $expenses = [
            new Expense(9 * 100, "Bouteille d'eau", $alice, $collection1, $group),
            new Expense(6 * 100, 'Sandwich', $charles, $collection2, $group),
            new Expense(12 * 100, 'Nourriture', $charles, $collection3, $group),
            new Expense(36 * 100, 'Essence', $camille, $collection1, $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }

        $this->logger->info('Expenses of first scenario are loaded in DB');
    }

    private function loadSecondScenario(): void
    {
        $usersData = new ArrayCollection([
            $pierre = new User('Pierre', 'pierre@gmail.com', 'password'),
            $david = new User('David', 'david@gmail.com', 'password'),
            $emilie = new User('Emilie', 'emilie@gmail.com', 'password'),
            $florence = new User('Florence', 'florence@gmail.com', 'password'),
        ]);

        $this->entityManager->persist($pierre);
        $this->entityManager->persist($david);
        $this->entityManager->persist($emilie);
        $this->entityManager->persist($florence);

        $this->logger->info('User of second scenario are loaded in DB');

        $group = new Group('Second groupe', 'groupe test numero 2', $usersData);

        $this->entityManager->persist($group);

        $this->logger->info('Group of second scenario is loaded in DB');

        $participantsCollectionOne = new ArrayCollection([$david, $emilie, $florence]);

        $expenses = [
            new Expense(10 * 100, 'Taxi', $pierre, $participantsCollectionOne, $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }

        $this->logger->info('Expenses of first scenario are loaded in DB');
    }

    private function loadThirdScenario(): void
    {
        $usersData = new ArrayCollection([
            $george = new User('George', 'george@gmail.com', 'password'),
            $helene = new User('Helene', 'helene@gmail.com', 'password'),
        ]);

        $this->entityManager->persist($george);
        $this->entityManager->persist($helene);

        $this->logger->info('User of third scenario are loaded in DB');

        $group = new Group('Third groupe', 'groupe test numero 3', $usersData);
        $this->entityManager->persist($group);

        $this->logger->info('Group of third scenario is loaded in DB');

        $participantsCollectionOne = new ArrayCollection([$george, $helene]);

        $expenses = [
            new Expense(10 * 100, 'Petit dèj', $george, $participantsCollectionOne, $group),
            new Expense(15 * 100, 'Déjeuner', $helene, new ArrayCollection([$george]), $group),
            new Expense(20 * 100, 'Diner', $george, new ArrayCollection([$helene]), $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }
        $this->logger->info('Expenses of third scenario are loaded in DB');
    }

    private function loadFourthScenario(): void
    {
        $usersData = new ArrayCollection([
            $isabelle = new User('Isabelle', 'isabelle@gmail.com', 'password'),
            $julien = new User('Julien', 'julien@gmail.com', 'password'),
            $leo = new User('Leo', 'leo@gmail.com', 'password'),
        ]);

        $this->entityManager->persist($isabelle);
        $this->entityManager->persist($julien);
        $this->entityManager->persist($leo);

        $this->logger->info('User of fourth scenario are loaded in DB');

        $group = new Group('Fourth groupe', 'groupe test numero 4', $usersData);

        $this->entityManager->persist($group);

        $this->logger->info('Group of fourth scenario is loaded in DB');

        $participantsCollection = new ArrayCollection([$isabelle, $julien, $leo]);

        $expenses = [
            new Expense(50 * 100, 'Peinture', $isabelle, $participantsCollection, $group),
            new Expense(50 * 100, 'Faux gazon', $julien, $participantsCollection, $group),
            new Expense(50 * 100, 'Plomb', $leo, $participantsCollection, $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
        }

        $this->logger->info('Expenses of fourth scenario are loaded in DB');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadFirstScenario();
        $this->loadSecondScenario();
        $this->loadThirdScenario();
        $this->loadFourthScenario();
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
