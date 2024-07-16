<?php

namespace App\DTO;

use InvalidArgumentException;

class UserSignUpDTO
{
    private readonly string $username;
    private readonly string $email;
    private readonly string $password;

    public function __construct(array $data)
    {
        if (!isset($data['username'])) {
            throw new InvalidArgumentException('Username is required');
        }

        if (!is_string($data['username'])) {
            throw new InvalidArgumentException('Username must be a string');
        }

        $this->username = $data['username'];

        if (!isset($data['email'])) {
            throw new InvalidArgumentException('Email is required');
        }

        if (!is_string($data['email'])) {
            throw new InvalidArgumentException('Email must be a string');
        }

        $this->email = $data['email'];

        if (!isset($data['password'])) {
            throw new InvalidArgumentException('Password is required');
        }

        if (!is_string($data['password'])) {
            throw new InvalidArgumentException('Password must be a string');
        }

        $this->password = $data['password'];
    }
}
