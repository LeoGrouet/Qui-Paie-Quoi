<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[OneToMany(targetEntity: Expense::class, mappedBy: 'payer')]
    private Collection $expenses;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255)]
        private string $name,

        #[ORM\Column(type: 'string', length: 60)]
        private string $email
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
