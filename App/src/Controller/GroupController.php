<?php

namespace App\Controller;

use App\Repository\ExpenseRepository;
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
    public function showGroup(int $id, ExpenseRepository $expenseRepository)
    {
        dump($id);
        $group = "test";
        dump($expenseRepository);

        $groups = $expenseRepository->findAll();
        dump($groups);

        return $this->render(
            'group.html.twig',
            [
                "group" => $group
            ]
        );
    }
}
