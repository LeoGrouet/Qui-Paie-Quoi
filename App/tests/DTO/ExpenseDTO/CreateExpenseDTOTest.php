<?php

namespace App\tests\DTO;

use App\DTO\ExpenseDTO\CreateExpenseDTO;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CreateExpenseDTOTest extends TestCase
{
    public function testCreateExpenseDTOAmount(): void
    {
        $expenseDTO = new CreateExpenseDTO();
        $expenseDTO->setAmount(100);
        $this->assertSame(100, $expenseDTO->getAmount());
    }

    public function testCreateExpenseDTODescription(): void
    {
        $expenseDTO = new CreateExpenseDTO();
        $expenseDTO->setDescription('Dinner');
        $this->assertSame('Dinner', $expenseDTO->getDescription());
    }

    public function testCreateExpenseDTOPayer(): void
    {
        $expenseDTO = new CreateExpenseDTO();
        $payer = new User('New user', 'newUser@gmail.com');
        $expenseDTO->setPayer($payer);
        $this->assertSame($payer, $expenseDTO->getPayer());
    }
}
