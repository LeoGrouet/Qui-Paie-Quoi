<?php

namespace App\Controller;

use App\Repository\GroupRepository;
use App\Service\GroupExpenseBalancer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/group', name: 'group', methods: ["GET"])]
class GroupController extends AbstractController
{
    #[Route('/', name: '_home', methods: ["GET"])]
    public function groups(): Response
    {
        return $this->render('group.html.twig');
    }

    #[Route('/{id}', name: '_id', methods: ["GET"], requirements: ['id' => Requirement::DIGITS])]
    public function showGroup(int $id, GroupRepository $groupRepository, GroupExpenseBalancer $groupExpenseBalancer)
    {
        $group = $groupRepository->findById($id);

        if ($group === null) {
            return $this->render('error.html.twig');
        }

        $groupName = $groupRepository->findById($id)->getName();
        $balances = $groupExpenseBalancer->showBalance($id);
        asort($balances);

        return $this->render(
            'group.html.twig',
            [
                'groupName' => $groupName,
                'balances' => $balances
            ]
        );
    }
}
