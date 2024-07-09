<?php

namespace App\Entity;

class Bilan
{
    private string $name;
    private int $cost = 0;
    private int $participation = 0;
    private int $balance = 0;

    /**
     * @var array<string, int>
     */
    private array $owe = [];

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

    /**
     * @return array<string, int>
     */
    public function getOwe(): array
    {
        return $this->owe;
    }

    /**
     * @param array<string, int> $owe
     */
    public function setOwe(array $owe): void
    {
        $this->owe = $owe;
    }
}
