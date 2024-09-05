<?php

namespace App\Controller;

use App\Entity\Group;
use App\Repository\GroupRepository;
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
            'group/groups.html.twig',
            [
                'user' => $user,
                'groups' => $groups,
            ]
        );
    }

    #[Route('/{id}', name: '_balance')]
    public function showBalance(
        Group $group,
    ): Response {
        $groupName = $group->getName();
        $groupId = $group->getId();
        $balances = $group->getUserBalances();

        return $this->render(
            'group/groupBalance.html.twig',
            [
                'groupId' => $groupId,
                'groupName' => $groupName,
                'balances' => $balances,
            ]
        );
    }

    #[Route('/{id}/expense', name: '_expenses')]
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
