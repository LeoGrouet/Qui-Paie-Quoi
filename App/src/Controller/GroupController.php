<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Service\GroupExpenseBalancer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groups', name: 'group', methods: ['GET'])]
class GroupController extends AbstractController
{
    #[Route('/', name: '_home', methods: ['GET'])]
    public function showGroups(GroupRepository $groupRepository): Response
    {
        $groups = $groupRepository->findAll();

        return $this->render(
            'groups.html.twig',
            [
                'groups' => $groups,
            ]
        );
    }

    #[Route('/{id}')]
    public function showBalances(
        Group $group,
        GroupExpenseBalancer $groupExpenseBalancer,
    ): Response {

        $balances = $groupExpenseBalancer->showBalance($group->getId());

        asort($balances);

        return $this->render(
            'groupBalances.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'balances' => $balances,
            ]
        );
    }

    #[Route('/{id}/expense')]
    public function showExpenses(
        Group $group,
    ): Response {

        $expenses = $group->getExpenses();

        foreach ($expenses as $expense) {
            $expense;
        }

        return $this->render(
            'groupExpenses.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'expenses' => $expenses,
            ]
        );
    }
}
