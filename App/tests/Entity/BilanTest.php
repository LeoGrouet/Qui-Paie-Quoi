<?php

namespace Tests\Entity;

use App\Entity\Bilan;
use PHPUnit\Framework\TestCase;

class BilanTest extends TestCase
{
    public function testBilanName(): void
    {
        $bilan = new Bilan('Bilan Name');

        $this->assertSame('Bilan Name', $bilan->getName());
    }

    public function testBilanCost(): void
    {
        $bilan = new Bilan('Bilan Name');
        $bilan->setCost(100);
        $this->assertSame(100, $bilan->getCost());
    }

    public function testBilanParticipation(): void
    {
        $bilan = new Bilan('Bilan Name');
        $bilan->setParticipation(100);
        $this->assertSame(100, $bilan->getParticipation());
    }

    public function testBilanBalance(): void
    {
        $bilan = new Bilan('Bilan Name');
        $bilan->setBalance(0);
        $this->assertSame(0, $bilan->getBalance());
    }

    public function testBilanOwe(): void
    {
        $bilan = new Bilan('Bilan Name');
        $this->assertSame([], $bilan->getOwe());
    }
}
