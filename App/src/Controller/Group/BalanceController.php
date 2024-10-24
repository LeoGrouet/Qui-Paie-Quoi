<?php

namespace App\Controller\Group;

use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BalanceController extends AbstractController
{
    #[Route('/group/{id}/balance', name: 'group_balance', methods: Request::METHOD_GET)]
    public function showBalance(
        Group $group,
    ): Response {
        return $this->render(
            'group/groupBalance.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'balances' => $group->getUserBalances(),
            ]
        );
    }
}
