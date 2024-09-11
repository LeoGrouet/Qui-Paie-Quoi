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
        $user = new User('New User', 'newuser@gmail.com');

        $this->assertSame('New User', $user->getUsername());
        $this->assertSame('newuser@gmail.com', $user->getEmail());
    }

    public function testUserEntityInterfaceImplementation(): void
    {
        $user = new User('New User', 'new@gmail.com');

        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testUserGetAndSetPassword(): void
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

    public function testUserSetUsername(): void
    {
        $user = new User('New User', 'new@gmail.com');
        $this->assertSame('New User', $user->getUsername());
        $user->setUsername('New User 2');
        $this->assertSame('New User 2', $user->getUsername());
    }

    public function testUserGetEmail(): void
    {
        $user = new User('New User', 'newuser@gmail.com');

        $this->assertSame('newuser@gmail.com', $user->getEmail());
    }

    public function testUserSetEmail(): void
    {
        $user = new User('New User', 'new@gmail.com');
        $this->assertSame('new@gmail.com', $user->getEmail());
        $user->setEmail('newnew@gmail.com');
        $this->assertSame('newnew@gmail.com', $user->getEmail());
    }

    public function testUserGetRoles(): void
    {
        $user = new User('New User', 'newuser@gmail.com');

        $this->assertSame(['ROLE_USER'], $user->getRoles());
    }

    public function testGetUserIdentifier(): void
    {
        $user = new User('New User', 'newuser@gmail.com');

        $this->assertSame('newuser@gmail.com', $user->getUserIdentifier());
    }

    public function testGetExpenses(): void
    {
        $usersData = new ArrayCollection([
            $user = new User('New User', 'newuser@gmail.com'),
            $user2 = new User('New User 2', 'newuser2@gmail.com'),
            $user3 = new User('New User 3', 'newuser3@gmail.com')
        ]);

        $group = new Group('test groupe', 'groupe test', $usersData);

        $participantsCollection = new ArrayCollection([$user, $user2, $user3]);

        new Expense(50 * 100, 'Peinture', $user, $participantsCollection, $group);
        new Expense(50 * 100, 'Faux gazon', $user2, $participantsCollection, $group);
        new Expense(50 * 100, 'Plomb', $user3, $participantsCollection, $group);

        $this->assertContainsOnlyInstancesOf(Expense::class, $user->getExpenses());
        $this->assertContainsOnlyInstancesOf(Expense::class, $user2->getExpenses());
        $this->assertContainsOnlyInstancesOf(Expense::class, $user3->getExpenses());
    }

    public function testSetExpenses(): void
    {
        $usersData = new ArrayCollection([
            $user = new User('New User', 'newuser@gmail.com'),
            $user2 = new User('New User 2', 'newuser2@gmail.com'),
            $user3 = new User('New User 3', 'newuser3@gmail.com')
        ]);

        $group = new Group('test groupe', 'groupe test', $usersData);

        $participantsCollection = new ArrayCollection([$user, $user2, $user3]);

        $expenses = new ArrayCollection([
            new Expense(50 * 100, 'Peinture', $user, $participantsCollection, $group),
        ]);

        $user->setExpenses($expenses);

        $this->assertContainsOnlyInstancesOf(Expense::class, $user->getExpenses());
    }
}
