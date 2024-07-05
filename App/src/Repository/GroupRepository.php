<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findIdByName(string $name): ?int
    {
        $result = $this->createQueryBuilder('g')
            ->select('g.id')
            ->where('g.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult();

        if (!is_int($result)) {
            return null;
        }
        return $result;
    }

    public function findOneById(int $id): ?Group
    {
        $group = $this->createQueryBuilder('g')
            ->where('g.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if ($group !== null && !$group instanceof Group) {
            return null;
        }

        return $group;
    }
}
