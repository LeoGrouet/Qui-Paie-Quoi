<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
use App\Service\GroupExpenseBalancer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/groups', name: 'groups', methods: ['GET'])]
class GroupController extends AbstractController
{
    #[Route('/', name: '_home', methods: ['GET'])]
    public function showGroups(
        GroupRepository $groupRepository,
        Security $security
    ): Response {
        $user = $security->getUser();

        $groups = $groupRepository->findAll();

        return $this->render(
            'groups.html.twig',
            [
                'user' => $user,
                'groups' => $groups,
            ]
        );
    }

    #[Route('/{id}', name: '_balance')]
    public function showBalance(
        Group $group,
        GroupExpenseBalancer $groupExpenseBalancer,
    ): Response {
        $balances = $groupExpenseBalancer->showBalance($group->getId());

        asort($balances);

        return $this->render(
            'groupBalance.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'balances' => $balances,
            ]
        );
    }

    #[Route('/{id}/expense', name: '_expenses')]
    public function showExpenses(
        Group $group,
    ): Response {
        return $this->render(
            'groupExpenses.html.twig',
            [
                'groupName' => $group->getName(),
                'groupId' => $group->getId(),
                'expenses' => $group->getExpenses(),
            ]
        );
    }
}
