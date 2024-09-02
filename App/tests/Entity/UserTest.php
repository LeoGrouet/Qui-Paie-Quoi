<?php

namespace Tests\Entity;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserTest extends TestCase
{
    public function testUserConstructor(): void
    {
        $user = new User('New User', "newuser@gmail.com");

        $this->assertSame('New User', $user->getUsername());
        $this->assertSame('newuser@gmail.com', $user->getEmail());
    }

    public function testUserEntityInterfaceImplementation(): void
    {
        $user = new User('New User', 'new@gmail.com');
        $this->assertInstanceOf(UserInterface::class, $user);

        $this->assertSame('New User', $user->getUsername());
    }

    public function testUserPassword(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $user->setPassword('password');
        $password = $user->getPassword();

        $this->assertSame('password', $password);
    }

    public function testUserGetUsername(): void
    {
        $user = new User('New User', 'new@gmail.com');
        $this->assertSame('New User', $user->getUsername());
    }

    // public function testGetExpense(): void
    // {
    //     $user = new User('New User', 'new@gmail.com');

    //     $expense = new Expense(100, 'test', $user, new ArrayCollection(), new Group('test', 'test', new ArrayCollection()));

    //     $this->assertSame($expense, $user->getExpenses());
    // }
}
