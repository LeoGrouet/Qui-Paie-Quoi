<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
#[ORM\Table(name: "`user`")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[JoinTable(name: 'users_groups')]
    private Collection|null $groups = null;

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'payer')]
    private Collection|null $expenses = null;


    public function __construct(
        #[ORM\Column(type: 'string', length: 60)]
        private string $name,

        #[ORM\Column(type: "string", length: 60)]
        private string $email,

        #[ORM\Column(type: "string", length: 60)]
        private string $password,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
