<?php

namespace App\DTO;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class ExpenseDTO
{
    private int $amount;
    private string $description;
    private User $payer;
    /**
     * @var Collection<int, \App\Entity\User>
     */
    private Collection $participants;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPayer(): User
    {
        return $this->payer;
    }

    public function setPayer(User $payer): void
    {
        $this->payer = $payer;
    }

    /**
     * @return Collection<int, \App\Entity\User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Collection<int, \App\Entity\User> $participants
     */
    public function setParticipants(Collection $participants): void
    {
        $this->participants = $participants;
    }
}
