<?php

namespace App\Controller\Group;

use App\Entity\Group;
use App\Service\GroupExpenseBalancer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BalanceController extends AbstractController
{
    #[Route('/groups/{id}/balance', name: 'groups_balance', methods: Request::METHOD_GET)]
    public function showBalance(
        Group $group,
        GroupExpenseBalancer $groupExpenseBalancer,
    ): Response {
        $balances = $groupExpenseBalancer->showBalance($group->getId());

        asort($balances);

        return $this->render(
            'group/groupBalance.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'balances' => $balances,
            ]
        );
    }
}
