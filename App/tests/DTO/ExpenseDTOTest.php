<?php

namespace App\tests\DTO;

use App\DTO\ExpenseDTO;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ExpenseDTOTest extends TestCase
{
    public function testExpenseDTOAmount(): void
    {
        $expenseDTO = new ExpenseDTO();
        $expenseDTO->setAmount(100);
        $this->assertSame(100, $expenseDTO->getAmount());
    }

    public function testExpenseDTODescription(): void
    {
        $expenseDTO = new ExpenseDTO();
        $expenseDTO->setDescription('Dinner');
        $this->assertSame('Dinner', $expenseDTO->getDescription());
    }

    public function testExpenseDTOPayer(): void
    {
        $expenseDTO = new ExpenseDTO();
        $payer = new User('New user', 'newUser@gmail.com');
        $expenseDTO->setPayer($payer);
        $this->assertSame($payer, $expenseDTO->getPayer());
    }
}
