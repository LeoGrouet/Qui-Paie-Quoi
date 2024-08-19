<?php

namespace Tests\Entity;

use App\Entity\Expense;
use App\Entity\Group;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserConstructor(): void
    {
        $user = new User('New User', "newuser@gmail.com");

        $this->assertSame('New User', $user->getUsername());
        $this->assertSame('newuser@gmail.com', $user->getEmail());
    }

    public function testUserPassword(): void
    {
        $user = new User('New User', 'newuser@gmail.com');
        $user->setPassword('password');
        $password = $user->getPassword();

        $this->assertSame('password', $password);
    }
}
