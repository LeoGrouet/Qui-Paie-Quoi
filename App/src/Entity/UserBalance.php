<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity]
class UserBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    public function __construct(

        #[ManyToOne(targetEntity: User::class)]
        #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
        private User $user,

        #[ManyToOne(targetEntity: Group::class, inversedBy: 'userBalances')]
        #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        private Group $group,

        #[ORM\Column(type: 'integer')]
        private int $amount
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function addAmount(int $amount): void
    {
        $this->amount += $amount;
    }

    public function __toString(): string
    {
        return $this->user->getEmail() . ' : ' . $this->amount;
    }
}
