<?php

namespace Tests\DTO;

use App\DTO\UserSignUpDTO;
use PHPUnit\Framework\TestCase;

class UserSignUpDTOTest extends TestCase
{
    public function testUserSignUpDTOUsername(): void
    {
        $userSignUpDTO = new UserSignUpDTO();
        $userSignUpDTO->setUsername('New User');
        $this->assertSame('New User', $userSignUpDTO->getUsername());
    }

    public function testUserSignUpDTOEmail(): void
    {
        $userSignUpDTO = new UserSignUpDTO();
        $userSignUpDTO->setEmail('newuser@gmail.com');
        $this->assertSame('newuser@gmail.com', $userSignUpDTO->getEmail());
    }

    public function testUserSignUpDTOPassword(): void
    {
        $userSignUpDTO = new UserSignUpDTO();
        $userSignUpDTO->setPassword('password');
        $this->assertSame('password', $userSignUpDTO->getPassword());
    }
}
