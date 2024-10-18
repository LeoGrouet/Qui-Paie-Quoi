<?php

namespace App\tests\Units\DTO\ExpenseDTO;

use App\DTO\ExpenseDTO\UpdateExpenseDTO;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UpdateExpenseDTOTest extends TestCase
{
    public function testUpdateExpenseDTOAmount(): void
    {
        $user = new User('New user', 'newuser@gmail.com');
        $user1 = new User('New user1', 'newuser1@gmail.com');
        $amount = 10;
        $description = 'Dinner';
        $participants = new ArrayCollection([$user, $user1]);

        $expenseDTO = new UpdateExpenseDTO($amount, $description, $user, $participants);
        $expenseDTO->setAmount(100);
        $this->assertSame(100, $expenseDTO->getAmount());
    }

    public function testUpdateExpenseDTODescription(): void
    {
        $user = new User('New user', 'newuser@gmail.com');
        $user1 = new User('New user1', 'newuser1@gmail.com');
        $amount = 100;
        $description = 'Dinner';
        $participants = new ArrayCollection([$user, $user1]);

        $expenseDTO = new UpdateExpenseDTO($amount, $description, $user, $participants);
        $expenseDTO->setDescription('Nouvelle dépense');
        $this->assertSame('Nouvelle dépense', $expenseDTO->getDescription());
    }

    public function testUpdateExpenseDTOPayer(): void
    {
        $user = new User('New user', 'newuser@gmail.com');
        $user1 = new User('New user1', 'newuser1@gmail.com');
        $amount = 100;
        $description = 'Dinner';
        $participants = new ArrayCollection([$user, $user1]);

        $expenseDTO = new UpdateExpenseDTO($amount, $description, $user, $participants);
        $expenseDTO->setPayer($user1);
        $this->assertSame($user1, $expenseDTO->getPayer());
    }

    public function testUpdateExpenseDTOParticipants(): void
    {
        $user = new User('New user', 'newuser@gmail.com');
        $user1 = new User('New user1', 'newuser1@gmail.com');
        $user2 = new User('New user2', 'newuser2@gmail.com');
        $amount = 100;
        $description = 'Dinner';
        $participants = new ArrayCollection([$user, $user1]);
        $newParticipantsCollection = new ArrayCollection([$user, $user1, $user2]);

        $expenseDTO = new UpdateExpenseDTO($amount, $description, $user, $participants);
        $expenseDTO->setParticipants($newParticipantsCollection);
        $this->assertSame($newParticipantsCollection, $expenseDTO->getParticipants());
    }
}
