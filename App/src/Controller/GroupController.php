<?php

namespace App\Controller;

use App\Entity\Group;
use App\Service\GroupExpenseBalancer;
use Symfony\Bridge\Doctrine\ArgumentResolver\EntityValueResolver;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group', name: 'group', methods: ["GET"])]
class GroupController extends AbstractController
{
    #[Route('/', name: '_home', methods: ["GET"])]
    public function groups(): Response
    {
        return $this->render('group.html.twig');
    }

    #[Route('/{id}')]
    public function show(
        Group $group,
        GroupExpenseBalancer $groupExpenseBalancer,
    ): Response {

        $balances = $groupExpenseBalancer->showBalance($group->getId());

        asort($balances);

        return $this->render(
            'group.html.twig',
            [
                'groupName' => $group->getName(),
                'balances' => $balances
            ]
        );
    }
}
