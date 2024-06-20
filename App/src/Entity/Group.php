<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
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

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'group')]
    private Collection|null $expenses = null;

    public function __construct(
        #[ORM\Column(type: 'string', length: 60)]
        private string $name,

        #[ORM\Column(type: 'string', length: 180)]
        private string $description,

        #[JoinTable(name: 'groups_users')]
        #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
        #[InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
        #[ManyToMany(targetEntity: 'User')]
        private Collection $users,
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

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function __toString()
    {
        return $this->name;
    }
}
