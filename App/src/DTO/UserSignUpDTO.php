<?php

namespace App\DTO;

readonly class UserSignUpDTO
{
    public string $username;
    public string $email;
    public string $password;

    public function __construct(mixed $username, mixed $email, mixed $password)
    {
        if (!is_string($username)) {
            throw new \InvalidArgumentException('Invalid username');
        }

        if (!is_string($email)) {
            throw new \InvalidArgumentException('Invalid email');
        }

        if (!is_string($password)) {
            throw new \InvalidArgumentException('Invalid password');
        }

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }
}
