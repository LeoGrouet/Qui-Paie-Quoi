<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    /**
     * @param int $groupId
     * @return Expense[]
     */
    public function findByGroupId(int $groupId): array
    {
        $result = $this->createQueryBuilder('expense')
            ->where('expense.group = :groupId')
            ->setParameter('groupId', $groupId)
            ->getQuery()
            ->getResult();

        if (!is_array($result)) {
            return [];
        }
        return $result;
    }
}
