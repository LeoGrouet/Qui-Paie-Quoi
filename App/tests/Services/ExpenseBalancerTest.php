<?php

namespace Tests\Services;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseBalancer;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class ExpenseBalancerTest extends TestCase
{
    public function testApply(): void
    {
        /**
         * @var ExpenseRepository
         */
        $expenseRepository = $this->createMock(ExpenseRepository::class);
        $service = new ExpenseBalancer($expenseRepository);

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
        dump($usersBalances);

        $this->assertSame(-1200, $usersBalances[0]->getAmount());
        $this->assertSame(-300, $usersBalances[1]->getAmount());
        $this->assertSame(1500, $usersBalances[2]->getAmount());
    }
}
