<?php

namespace App\Command;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Service\ExpenseBalancer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:insertInDB')]
class DatabaseFlushTest extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ExpenseBalancer $expenseBalancer
    ) {
        parent::__construct();
    }

    private function loadFirstScenario(): void
    {
        $usersData = new ArrayCollection([
            $alice = new User('Alice', 'alice@gmail.com'),
            $charles = new User('Charles', 'charles@gmail.com'),
            $camille = new User('Camille', 'camille@gmail.com'),
        ]);

        $alice->setPassword($this->userPasswordHasher->hashPassword($alice, 'password'));
        $charles->setPassword($this->userPasswordHasher->hashPassword($charles, 'password'));
        $camille->setPassword($this->userPasswordHasher->hashPassword($camille, 'password'));

        $this->entityManager->persist($alice);
        $this->entityManager->persist($charles);
        $this->entityManager->persist($camille);

        $this->logger->info('User of first scenario are loaded in DB');

        $group = new Group('First groupe', 'groupe test numero 1');

        $group->setUsers($usersData);

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
            $this->entityManager->flush();
        }

        foreach ($expenses as $expense) {
            $this->expenseBalancer->apply($expense);
        }

        $this->logger->info('Expenses of first scenario are loaded in DB');
    }

    private function loadSecondScenario(): void
    {
        $usersData = new ArrayCollection([
            $pierre = new User('Pierre', 'pierre@gmail.com'),
            $david = new User('David', 'david@gmail.com'),
            $emilie = new User('Emilie', 'emilie@gmail.com'),
            $florence = new User('Florence', 'florence@gmail.com'),
        ]);

        $pierre->setPassword($this->userPasswordHasher->hashPassword($pierre, 'password'));
        $david->setPassword($this->userPasswordHasher->hashPassword($david, 'password'));
        $emilie->setPassword($this->userPasswordHasher->hashPassword($emilie, 'password'));
        $florence->setPassword($this->userPasswordHasher->hashPassword($florence, 'password'));

        $this->entityManager->persist($pierre);
        $this->entityManager->persist($david);
        $this->entityManager->persist($emilie);
        $this->entityManager->persist($florence);

        $this->logger->info('User of second scenario are loaded in DB');

        $group = new Group('Second groupe', 'groupe test numero 2');

        $group->setUsers($usersData);

        $this->entityManager->persist($group);

        $this->logger->info('Group of second scenario is loaded in DB');

        $participantsCollectionOne = new ArrayCollection([$david, $emilie, $florence]);

        $expenses = [
            new Expense(10 * 100, 'Taxi', $pierre, $participantsCollectionOne, $group),
        ];

        foreach ($expenses as $expense) {
            $this->entityManager->persist($expense);
            $this->entityManager->flush();
        }

        foreach ($expenses as $expense) {
            $this->expenseBalancer->apply($expense);
        }

        $this->logger->info('Expenses of first scenario are loaded in DB');
    }

    private function loadThirdScenario(): void
    {
        $usersData = new ArrayCollection([
            $george = new User('George', 'george@gmail.com'),
            $helene = new User('Helene', 'helene@gmail.com'),
        ]);

        $george->setPassword($this->userPasswordHasher->hashPassword($george, 'password'));
        $helene->setPassword($this->userPasswordHasher->hashPassword($helene, 'password'));

        $this->entityManager->persist($george);
        $this->entityManager->persist($helene);

        $this->logger->info('User of third scenario are loaded in DB');

        $group = new Group('Third groupe', 'groupe test numero 3');

        $group->setUsers($usersData);

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
            $this->entityManager->flush();
        }

        foreach ($expenses as $expense) {
            $this->expenseBalancer->apply($expense);
        }

        $this->logger->info('Expenses of third scenario are loaded in DB');
    }

    private function loadFourthScenario(): void
    {
        $usersData = new ArrayCollection([
            $isabelle = new User('Isabelle', 'isabelle@gmail.com'),
            $julien = new User('Julien', 'julien@gmail.com'),
            $leo = new User('Leo', 'leo@gmail.com'),
        ]);

        $isabelle->setPassword($this->userPasswordHasher->hashPassword($isabelle, 'password'));
        $julien->setPassword($this->userPasswordHasher->hashPassword($julien, 'password'));
        $leo->setPassword($this->userPasswordHasher->hashPassword($leo, 'password'));

        $this->entityManager->persist($isabelle);
        $this->entityManager->persist($julien);
        $this->entityManager->persist($leo);

        $this->logger->info('User of fourth scenario are loaded in DB');

        $group = new Group('Fourth groupe', 'groupe test numero 4');

        $group->setUsers($usersData);

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
            $this->entityManager->flush();
        }

        foreach ($expenses as $expense) {
            $this->expenseBalancer->apply($expense);
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
