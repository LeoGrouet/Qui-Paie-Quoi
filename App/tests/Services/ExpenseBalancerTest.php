<?php

namespace Tests\Services;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Repository\UserBalanceRepository;
use App\Service\ExpenseBalancer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ExpenseBalancerTest extends TestCase
{
    public function testApplyFirstScenario(): void
    {
        /**
         * @var ExpenseRepository $expenseRepository
         */
        $expenseRepository = $this->createMock(ExpenseRepository::class);
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
        $service = new ExpenseBalancer($entityManager, $expenseRepository, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $alice = new User('Alice', 'alice@gmail.com'),
            $charles = new User('Charles', 'charles@gmail.com'),
            $camille = new User('Camille', 'camille@gmail.com'),
        ]);

        $group = new Group('First groupe', 'groupe test numero 1', $usersData);

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
            $service->apply($expense);
        }

        $usersBalances = $group->getUserBalances();

        $this->assertSame(-1200, $usersBalances[0]->getAmount());
        $this->assertSame(-300, $usersBalances[1]->getAmount());
        $this->assertSame(1500, $usersBalances[2]->getAmount());
    }

    // public function testApplySecondScenario(): void
    // {
    //     /**
    //      * @var ExpenseRepository $expenseRepository
    //      */
    //     $expenseRepository = $this->createMock(ExpenseRepository::class);
    //     /**
    //      * @var EntityManagerInterface $entityManager
    //      */
    //     $entityManager = $this->createMock(EntityManagerInterface::class);
    //     /**
    //      * @var UserBalanceRepository $userBalanceRepository
    //      */
    //     $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
    //     $service = new ExpenseBalancer($entityManager, $expenseRepository, $userBalanceRepository);

    //     $usersData = new ArrayCollection([
    //         $pierre = new User('Pierre', 'pierre@gmail.com'),
    //         $david = new User('David', 'david@gmail.com'),
    //         $emilie = new User('Emilie', 'emilie@gmail.com'),
    //         $florence = new User('Florence', 'florence@gmail.com'),
    //     ]);

    //     $group = new Group('Second groupe', 'groupe test numero 2', $usersData);

    //     $participantsCollectionOne = new ArrayCollection([$david, $emilie, $florence]);

    //     $expenses = [
    //         new Expense(10 * 100, 'Taxi', $pierre, $participantsCollectionOne, $group),
    //     ];

    //     foreach ($expenses as $expense) {
    //         $service->apply($expense);
    //     }

    //     $usersBalances = $group->getUserBalances();

    //     $this->assertSame(1000, $usersBalances[0]->getAmount());
    //     $this->assertSame(-333, $usersBalances[1]->getAmount());
    //     $this->assertSame(-333, $usersBalances[2]->getAmount());
    //     $this->assertSame(-333, $usersBalances[3]->getAmount());
    // }

    // public function testApplyThirdScenario(): void
    // {
    //     /**
    //      * @var ExpenseRepository $expenseRepository
    //      */
    //     $expenseRepository = $this->createMock(ExpenseRepository::class);
    //     /**
    //      * @var EntityManagerInterface $entityManager
    //      */
    //     $entityManager = $this->createMock(EntityManagerInterface::class);
    //     /**
    //      * @var UserBalanceRepository $userBalanceRepository
    //      */
    //     $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
    //     $service = new ExpenseBalancer($entityManager, $expenseRepository, $userBalanceRepository);

    //     $usersData = new ArrayCollection([
    //         $george = new User('George', 'george@gmail.com'),
    //         $helene = new User('Helene', 'helene@gmail.com'),
    //     ]);

    //     $group = new Group('Third groupe', 'groupe test numero 3', $usersData);

    //     $participantsCollectionOne = new ArrayCollection([$george, $helene]);

    //     $expenses = [
    //         new Expense(10 * 100, 'Petit dèj', $george, $participantsCollectionOne, $group),
    //         new Expense(15 * 100, 'Déjeuner', $helene, new ArrayCollection([$george]), $group),
    //         new Expense(20 * 100, 'Diner', $george, new ArrayCollection([$helene]), $group),
    //     ];

    //     foreach ($expenses as $expense) {
    //         $service->apply($expense);
    //     }

    //     $usersBalances = $group->getUserBalances();

    //     $this->assertSame(1000, $usersBalances[0]->getAmount());
    //     $this->assertSame(-1000, $usersBalances[1]->getAmount());
    // }

    // public function testApplyFourthScenario(): void
    // {
    //     /**
    //      * @var ExpenseRepository $expenseRepository
    //      */
    //     $expenseRepository = $this->createMock(ExpenseRepository::class);
    //     /**
    //      * @var EntityManagerInterface $entityManager
    //      */
    //     $entityManager = $this->createMock(EntityManagerInterface::class);
    //     /**
    //      * @var UserBalanceRepository $userBalanceRepository
    //      */
    //     $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
    //     $service = new ExpenseBalancer($entityManager, $expenseRepository, $userBalanceRepository);

    //     $usersData = new ArrayCollection([
    //         $isabelle = new User('Isabelle', 'isabelle@gmail.com'),
    //         $julien = new User('Julien', 'julien@gmail.com'),
    //         $leo = new User('Leo', 'leo@gmail.com'),
    //     ]);

    //     $group = new Group('Fourth groupe', 'groupe test numero 4', $usersData);

    //     $participantsCollection = new ArrayCollection([$isabelle, $julien, $leo]);

    //     $expenses = [
    //         new Expense(50 * 100, 'Peinture', $isabelle, $participantsCollection, $group),
    //         new Expense(50 * 100, 'Faux gazon', $julien, $participantsCollection, $group),
    //         new Expense(50 * 100, 'Plomb', $leo, $participantsCollection, $group),
    //     ];
    //     foreach ($expenses as $expense) {
    //         $service->apply($expense);
    //     }

    //     $usersBalances = $group->getUserBalances();

    //     $this->assertSame(2, $usersBalances[0]->getAmount());
    //     $this->assertSame(2, $usersBalances[1]->getAmount());
    //     $this->assertSame(2, $usersBalances[2]->getAmount());
    // }
}
