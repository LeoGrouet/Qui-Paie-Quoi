<?php

namespace App\DTO;

use Doctrine\Common\Collections\Collection;

class GroupDTO
{
    private string $name;
    private string $description;
    private ?Collection $users;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }
}
