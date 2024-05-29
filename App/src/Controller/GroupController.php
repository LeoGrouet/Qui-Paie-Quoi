<?php

namespace App\Controller;

use App\Repository\ExpenseRepository;
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
    public function showGroup(int $id, ExpenseRepository $expenseRepository, GroupExpenseBalancer $groupExpenseBalancer)
    {

        $expenses = $expenseRepository->getExpensesOfGroupById($id);

        $bilans = $groupExpenseBalancer->expenseBalancer($expenses);

        $balances = [];

        foreach ($bilans as $bilan) {
            $name = $bilan->getName();
            $owe = $bilan->getOwe();

            foreach ($owe as $key => $values) {
                $formatedValue = $values / 100;
                array_push($balances, ("{$key} doit {$formatedValue} euros à {$name}"));
            }
        }

        return $this->render(
            'group.html.twig',
            [
                'balances' => $balances
            ]
        );
    }
}
