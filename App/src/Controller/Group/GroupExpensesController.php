<?php

namespace App\Controller\Group;

use App\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupExpensesController extends AbstractController
{
    #[Route('/groups/{id}/expense', name: 'groups_expenses', methods: ['GET'])]
    public function showExpenses(
        Group $group,
    ): Response {
        return $this->render(
            'group/groupExpenses.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'expenses' => $group->getExpenses(),
            ]
        );
    }
}
