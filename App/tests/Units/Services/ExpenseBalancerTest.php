<?php

namespace Tests\Units\Services;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
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
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createStub(UserBalanceRepository::class);

        $service = new ExpenseBalancer($entityManager, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $alice = new User('Alice', 'alice@gmail.com'),
            $charles = new User('Charles', 'charles@gmail.com'),
            $camille = new User('Camille', 'camille@gmail.com'),
        ]);

        $usersGroup = new Group('First groupe', 'groupe test numero 1', $usersData);

        $collection1 = new ArrayCollection([$alice, $charles, $camille]);
        $collection2 = new ArrayCollection([$charles]);
        $collection3 = new ArrayCollection([$alice, $camille]);

        $expenses = [
            new Expense(9 * 100, "Bouteille d'eau", $alice, $collection1, $usersGroup),
            new Expense(6 * 100, 'Sandwich', $charles, $collection2, $usersGroup),
            new Expense(12 * 100, 'Nourriture', $charles, $collection3, $usersGroup),
            new Expense(36 * 100, 'Essence', $camille, $collection1, $usersGroup),
        ];

        $aliceBalance = new UserBalance($alice, $usersGroup);
        $charlesBalance = new UserBalance($charles, $usersGroup);
        $camilleBalance = new UserBalance($camille, $usersGroup);

        $userBalanceRepository->method('getUserBalance')
            ->willReturnCallback(fn(User $user, Group $group) => match ([$user, $group]) {
                [$alice, $usersGroup] => $aliceBalance,
                [$charles, $usersGroup] => $charlesBalance,
                [$camille, $usersGroup] => $camilleBalance,
            });

        foreach ($expenses as $expense) {
            $service->apply($expense);
        }

        $this->assertSame(-1200, $aliceBalance->getAmount());
        $this->assertSame(-300, $charlesBalance->getAmount());
        $this->assertSame(1500, $camilleBalance->getAmount());
        $this->assertSame(0, $aliceBalance->getAmount() + $charlesBalance->getAmount() + $camilleBalance->getAmount());
    }


    public function testApplySecondScenario(): void
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
        $service = new ExpenseBalancer($entityManager, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $pierre = new User('Pierre', 'pierre@gmail.com'),
            $david = new User('David', 'david@gmail.com'),
            $emilie = new User('Emilie', 'emilie@gmail.com'),
            $florence = new User('Florence', 'florence@gmail.com'),
        ]);

        $usersGroup = new Group('Second groupe', 'groupe test numero 2', $usersData);

        $reflectionClass = new \ReflectionClass(Group::class);

        $reflectionClass->getProperty('id')->setValue($usersGroup, 1);

        $participantsCollectionOne = new ArrayCollection([$david, $emilie, $florence]);

        $expenses = [
            new Expense(10 * 100, 'Taxi', $pierre, $participantsCollectionOne, $usersGroup),
        ];

        $pierreBalance = new UserBalance($pierre, $usersGroup);
        $davidBalance = new UserBalance($david, $usersGroup);
        $emilieBalance = new UserBalance($emilie, $usersGroup);
        $florenceBalance = new UserBalance($florence, $usersGroup);

        $userBalanceRepository->method('getUserBalance')
            ->willReturnCallback(fn(User $user, Group $group) => match ([$user, $group]) {
                [$pierre, $usersGroup] => $pierreBalance,
                [$david, $usersGroup] => $davidBalance,
                [$emilie, $usersGroup] => $emilieBalance,
                [$florence, $usersGroup] => $florenceBalance,
            });

        $userBalanceRepository->method('getHighestUserBalanceOfGroup')
            ->willReturnCallback(fn(int $id) => match ($id) {
                1 => $pierreBalance,
            });

        foreach ($expenses as $expense) {
            $service->apply($expense);
        }

        $this->assertSame(999, $pierreBalance->getAmount());
        $this->assertSame(-333, $davidBalance->getAmount());
        $this->assertSame(-333, $emilieBalance->getAmount());
        $this->assertSame(-333, $florenceBalance->getAmount());
    }

    public function testApplyThirdScenario(): void
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createMock(UserBalanceRepository::class);
        $service = new ExpenseBalancer($entityManager, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $george = new User('George', 'george@gmail.com'),
            $helene = new User('Helene', 'helene@gmail.com'),
        ]);

        $usersGroup = new Group('Third groupe', 'groupe test numero 3', $usersData);

        $participantsCollectionOne = new ArrayCollection([$george, $helene]);

        $expenses = [
            new Expense(10 * 100, 'Petit dèj', $george, $participantsCollectionOne, $usersGroup),
            new Expense(15 * 100, 'Déjeuner', $helene, new ArrayCollection([$george]), $usersGroup),
            new Expense(20 * 100, 'Diner', $george, new ArrayCollection([$helene]), $usersGroup),
        ];

        $georgeBalance = new UserBalance($george, $usersGroup);
        $heleneBalance = new UserBalance($helene, $usersGroup);

        $userBalanceRepository->method('getUserBalance')
            ->willReturnCallback(fn(User $user, Group $group) => match ([$user, $group]) {
                [$george, $usersGroup] => $georgeBalance,
                [$helene, $usersGroup] => $heleneBalance,
            });

        foreach ($expenses as $expense) {
            $service->apply($expense);
        }

        $this->assertSame(1000, $georgeBalance->getAmount());
        $this->assertSame(-1000, $heleneBalance->getAmount());
        $this->assertSame(0, $georgeBalance->getAmount() + $heleneBalance->getAmount());
    }

    public function testApplyFourthScenario(): void
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createMock(UserBalanceRepository::class);

        $service = new ExpenseBalancer($entityManager, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $isabelle = new User('Isabelle', 'isabelle@gmail.com'),
            $julien = new User('Julien', 'julien@gmail.com'),
            $leo = new User('Leo', 'leo@gmail.com'),
        ]);

        $usersGroup = new Group('Fourth groupe', 'groupe test numero 4', $usersData);

        $participantsCollection = new ArrayCollection([$isabelle, $julien, $leo]);

        $expenses = [
            new Expense(50 * 100, 'Peinture', $isabelle, $participantsCollection, $usersGroup),
            new Expense(50 * 100, 'Faux gazon', $julien, $participantsCollection, $usersGroup),
            new Expense(50 * 100, 'Plomb', $leo, $participantsCollection, $usersGroup),
        ];

        $isabelleBalance = new UserBalance($isabelle, $usersGroup);
        $julienBalance = new UserBalance($julien, $usersGroup);
        $leoBalance = new UserBalance($leo, $usersGroup);

        $reflectionClass = new \ReflectionClass(Group::class);

        $reflectionClass->getProperty('id')->setValue($usersGroup, 1);

        $userBalanceRepository->method('getUserBalance')
            ->willReturnCallback(fn(User $user, Group $group) => match ([$user, $group]) {
                [$isabelle, $usersGroup] => $isabelleBalance,
                [$julien, $usersGroup] => $julienBalance,
                [$leo, $usersGroup] => $leoBalance,
            });

        $userBalanceRepository->expects($this->exactly(3))->method('getHighestUserBalanceOfGroup')
            ->willReturnOnConsecutiveCalls($isabelleBalance, $julienBalance, $leoBalance);

        foreach ($expenses as $expense) {
            $service->apply($expense);
        }

        $this->assertSame(0, $isabelleBalance->getAmount());
        $this->assertSame(0, $julienBalance->getAmount());
        $this->assertSame(0, $leoBalance->getAmount());
    }

    public function testUpdateBalance(): void
    {
        /**
         * @var EntityManagerInterface $entityManager
         */
        $entityManager = $this->createMock(EntityManagerInterface::class);
        /**
         * @var UserBalanceRepository $userBalanceRepository
         */
        $userBalanceRepository = $this->createMock(UserBalanceRepository::class);

        $service = new ExpenseBalancer($entityManager, $userBalanceRepository);

        $usersData = new ArrayCollection([
            $alice = new User('Alice', 'alice@gmail.com'),
            $charles = new User('Charles', 'charles@gmail.com'),
            $camille = new User('Camille', 'camille@gmail.com'),
        ]);

        $usersGroup = new Group('First groupe', 'groupe test numero 1', $usersData);

        $collection1 = new ArrayCollection([$alice, $charles, $camille]);
        $collection2 = new ArrayCollection([$charles]);
        $collection3 = new ArrayCollection([$alice, $camille]);

        $expenses = [
            new Expense(9 * 100, "Bouteille d'eau", $alice, $collection1, $usersGroup),
            new Expense(6 * 100, 'Sandwich', $charles, $collection2, $usersGroup),
            new Expense(12 * 100, 'Nourriture', $charles, $collection3, $usersGroup),
            new Expense(36 * 100, 'Essence', $camille, $collection1, $usersGroup),
        ];

        $aliceBalance = new UserBalance($alice, $usersGroup);
        $charlesBalance = new UserBalance($charles, $usersGroup);
        $camilleBalance = new UserBalance($camille, $usersGroup);

        $userBalanceRepository->method('getUserBalance')
            ->willReturnCallback(fn(User $user, Group $group) => match ([$user, $group]) {
                [$alice, $usersGroup] => $aliceBalance,
                [$charles, $usersGroup] => $charlesBalance,
                [$camille, $usersGroup] => $camilleBalance,
            });

        foreach ($expenses as $expense) {
            $service->apply($expense);
        }

        $expensesUpdates = new ArrayCollection([
            new Expense(15 * 100, "Bouteille d'eau", $alice, $collection1, $usersGroup),
            new Expense(6 * 100, 'Sandwich', $charles, $collection2, $usersGroup),
            new Expense(12 * 100, 'Nourriture', $charles, $collection3, $usersGroup),
            new Expense(36 * 100, 'Essence', $camille, $collection1, $usersGroup),
        ]);

        $usersBalance = new ArrayCollection([$aliceBalance, $charlesBalance, $camilleBalance]);

        $service->updateBalances($usersBalance, $expensesUpdates);

        $this->assertSame(-800, $aliceBalance->getAmount());
        $this->assertSame(-500, $charlesBalance->getAmount());
        $this->assertSame(1300, $camilleBalance->getAmount());
        $this->assertSame(0, $aliceBalance->getAmount() + $charlesBalance->getAmount() + $camilleBalance->getAmount());
    }
}
