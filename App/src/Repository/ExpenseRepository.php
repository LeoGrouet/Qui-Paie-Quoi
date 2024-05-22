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

    public function getAllExpenses()
    {
        return $this->createQueryBuilder('expense')
            ->getQuery()
            ->getResult();
    }

    public function getExpensesOfGroupById($id)
    {
        // Should return all the expenses where the group id is : $id
        return $this->createQueryBuilder('expense')
            ->join('expense.user', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
