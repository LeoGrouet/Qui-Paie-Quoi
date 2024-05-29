<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserByName(string $name): User
    {
        return $this->createQueryBuilder('user')
            ->where('user.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findParticipantsByExpenseId(int $id): array
    {
        return $this->createQueryBuilder('expenses_users')
            ->where('expense_id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
