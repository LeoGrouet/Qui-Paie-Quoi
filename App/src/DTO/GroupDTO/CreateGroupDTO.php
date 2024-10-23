<?php

namespace App\DTO\GroupDTO;

use Doctrine\Common\Collections\Collection;

class CreateGroupDTO
{
    private string $name;
    private string $description;

    /**
     * @var Collection<int, \App\Entity\User>
     */
    private Collection $users;

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

    /**
     * @return Collection<int, \App\Entity\User>
     */
    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    /**
     * @param Collection<int, \App\Entity\User> $users
     */
    public function setUsers(Collection $users): void
    {
        $this->users = $users;
    }
}
