<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
#[ORM\Table(name: "`group`")]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ManyToMany(targetEntity: User::class, mappedBy: 'groups')]
    private Collection $users;

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'group')]
    private Collection $expenses;

    public function __construct(
        #[ORM\Column(type: 'string', length: 60)]
        private string $name,

        #[ORM\Column(type: 'string', length: 180)]
        private string $description,

    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getUser(): Collection
    {
        return $this->users;
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }
}
