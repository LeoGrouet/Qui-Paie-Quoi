<?php

namespace App\Entity;

class Bilan
{
    protected string $name;
    protected int $cost = 0;
    protected int $participation = 0;
    protected int $balance = 0;
    protected array $owe = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    public function getParticipation(): int
    {
        return $this->participation;
    }

    public function setParticipation(int $participation): void
    {
        $this->participation = $participation;
    }

    public function getOwe(): array
    {
        return $this->owe;
    }

    public function setOwe(array $owe): void
    {
        $this->owe = $owe;
    }
}
