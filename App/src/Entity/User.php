<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    /**
     * @var Collection<int, Expense>
     */
    #[OneToMany(targetEntity: Expense::class, mappedBy: 'payer')]
    private Collection $expenses;

    public function __construct(
        #[ORM\Column(type: 'string', length: 255)]
        private string $username,

        #[ORM\Column(type: 'string', length: 320, unique: true)]
        private string $email,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    /**
     * @param Collection<int, Expense> $expenses
     */
    public function setExpenses(Collection $expenses): void
    {
        $this->expenses = $expenses;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $newName): void
    {
        $this->username = $newName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $newEmail): void
    {
        $this->email = $newEmail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $newPassword): void
    {
        $this->password = $newPassword;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Nothin to clear here
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
