<?php

namespace Tests\Entity;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserBalanceTest extends TestCase
{

    public function testUserBalanceGetUser(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $group = new Group('New Group', 'Group description', new ArrayCollection([$user]), new ArrayCollection([]));
        $userBalance = new UserBalance($user, $group);

        $this->assertSame($user, $userBalance->getUser());
    }

    public function testUserBalanceGetAmount(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $group = new Group('New Group', 'Group description', new ArrayCollection([$user]), new ArrayCollection([]));
        $userBalance = new UserBalance($user, $group);

        $this->assertSame(0, $userBalance->getAmount());
    }

    public function testUserBalanceAddAmount(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $group = new Group('New Group', 'Group description', new ArrayCollection([$user]), new ArrayCollection([]));
        $userBalance = new UserBalance($user, $group);
        $userBalance->addAmount(100);

        $this->assertSame(100, $userBalance->getAmount());
    }

    public function testUserBalanceAddDebt(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $group = new Group('New Group', 'Group description', new ArrayCollection([$user]), new ArrayCollection([]));
        $userBalance = new UserBalance($user, $group);
        $userBalance->addAmount(100);

        $userBalance->addDebt(50);

        $this->assertSame(50, $userBalance->getAmount());
    }
}
