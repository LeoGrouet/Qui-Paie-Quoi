<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function getAllExpenses(): Collection
    {
        return $this->createQueryBuilder('expense')
            ->getQuery()
            ->getResult();
    }

    public function getExpensesOfGroupById(int $id): array
    {
        return $this->createQueryBuilder('expense')
            ->where('expense.group = :group')
            ->setParameter('group', $id)
            ->getQuery()
            ->getResult();
    }
}
