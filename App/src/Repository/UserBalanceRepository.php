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

    public function getUserBalance(User $user, Group $group): UserBalance
    {
        $userBalance = $this->findOneBy(['group' => $group, 'user' => $user]);
        if (!$userBalance instanceof UserBalance) {
            $userBalance = new UserBalance($user, $group);

            $this->getEntityManager()->persist($userBalance);
        }

        return $userBalance;
    }
}
