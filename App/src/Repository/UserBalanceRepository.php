<?php

namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBalance>
 */
class UserBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBalance::class);
    }

    public function getUserBalance(User $user, Group $group): ?UserBalance
    {
        return $this->findOneBy(['group' => $group, 'user' => $user]);
    }

    public function getHighestUserBalanceOfGroup(int $groupId): UserBalance
    {
        $userBalance = $this->createQueryBuilder('userBalance')
            ->join('userBalance.group', 'g')
            ->where('g.id = :groupId')
            ->setParameter('groupId', $groupId)
            ->orderBy('userBalance.amount', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $userBalance || !$userBalance instanceof UserBalance) {
            throw new \Exception('Aucun userBalance trouvé pour ce groupe.');
        }

        return $userBalance;
    }
}
