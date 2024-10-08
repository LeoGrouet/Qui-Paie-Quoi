<?php

namespace App\Controller\Group;

use App\DTO\ExpenseDTO;
use App\Entity\Expense;
use App\Entity\Group;
use App\Form\ExpenseType;
use App\Service\ExpenseBalancer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExpensesController extends AbstractController
{
    #[Route('/group/{id}/expense', name: 'group_expenses', methods: Request::METHOD_GET)]
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

    #[Route('/group/{id}/expense/add', name: 'add_expense', methods: ['GET', 'POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $entityManagerInterface,
        TranslatorInterface $translator,
        Group $group,
        ExpenseBalancer $expenseBalancer
    ): Response {
        $form = $this->createForm(ExpenseType::class, null, [
            'group' => $group,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof ExpenseDTO) {
                $this->addFlash(
                    'notice',
                    $translator->trans('errorExpense', [], 'addExpense')
                );

                return $this->redirectToRoute('add_expense');
            }

            $expense = new Expense(
                $data->getAmount(),
                $data->getDescription(),
                $data->getPayer(),
                $data->getParticipants(),
                $group
            );

            $this->addFlash(
                'success',
                $translator->trans('succesExpense', [], 'addExpense')
            );

            $expenseBalancer->apply($expense);
            $entityManagerInterface->persist($expense);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('group_expenses', ['id' => $group->getId()]);
        }

        return $this->render(
            'expense/addExpense.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('/group/expense/{id}/edit', name: 'update_expense', methods: ['GET', 'POST'])]
    public function update(
        Expense $expense,
    ): Response {

        $expenseToUpdate = new ExpenseDTO($expense->getAmount(), $expense->getDescription(), $expense->getPayer(), $expense->getParticipants());

        $form = $this->createForm(
            ExpenseType::class,
            $expenseToUpdate,
            [
                'group' => $expense->getGroup(),
                'user' => $expense->getPayer(),
            ]
        );

        return $this->render(
            'expense/updateExpense.html.twig',
            [
                'expense' => $expense,
                'form' => $form,
            ]
        );
    }
}
