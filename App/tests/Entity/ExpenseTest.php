<?php

namespace Tests\Entity;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class ExpenseTest extends TestCase
{
    public function testExpenseConstructor(): void
    {
        $expense = new Expense(100, 'Expense Name', new User('New User', 'newuser@gmail.com'), new ArrayCollection([]), new Group('Group Name', 'Description', new ArrayCollection([])));

        $this->assertSame(100, $expense->getAmount());
        $this->assertSame('Expense Name', $expense->getDescription());
        $this->assertInstanceOf(User::class, $expense->getPayer());
        $this->assertInstanceOf(Collection::class, $expense->getParticipants());
        $this->assertInstanceOf(Group::class, $expense->$this->group);
    }
}
