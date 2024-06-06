<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function findByGroupId(int $groupId): array
    {
        return $this->createQueryBuilder('expense')
            ->where('expense.group = :groupId')
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getResult();
    }
}
