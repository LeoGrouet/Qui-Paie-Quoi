<?php

namespace App\Controller\Group;

use App\DTO\ExpenseDTO\CreateExpenseDTO;
use App\DTO\ExpenseDTO\UpdateExpenseDTO;
use App\Entity\Expense;
use App\Entity\Group;
use App\Form\ExpenseType;
use App\Service\ExpenseBalancer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

            if (!$data instanceof CreateExpenseDTO) {
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

    #[Route('/group/{groupId}/expense/{expenseId}/edit', name: 'update_expense', methods: ['GET', 'PUT'])]
    public function edit(
        #[MapEntity(mapping: ['expenseId' => 'id'])]
        Expense $expense,
        Request $request,
        TranslatorInterface $translator,
        EntityManagerInterface $entityManagerInterface,
        ExpenseBalancer $expenseBalancer
    ): Response {
        $expenseToUpdate = new UpdateExpenseDTO($expense->getAmount(), $expense->getDescription(), $expense->getPayer(), $expense->getParticipants());

        $form = $this->createForm(
            ExpenseType::class,
            $expenseToUpdate,
            [
                'group' => $expense->getGroup(),
                'user' => $expense->getPayer(),
            ]
        );

        $form->handleRequest($request);
        // dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (!$data instanceof UpdateExpenseDTO) {
                $this->addFlash(
                    'notice',
                    $translator->trans('errorExpense', [], 'addExpense')
                );

                return $this->redirectToRoute('update_expense');
            }

            $expense->setAmount($data->getAmount());
            $expense->setDescription($data->getDescription());
            $expense->setPayer($data->getPayer());
            $expense->setParticipants($data->getParticipants());

            $entityManagerInterface->persist($expense);
            $entityManagerInterface->flush();

            $usersBalance = $expense->getGroup()->getUserBalances();

            foreach ($usersBalance as $userBalance) {
                $userBalance->setAmount(0);
                $entityManagerInterface->persist($userBalance);
            }

            $expenses = $expense->getGroup()->getExpenses();

            foreach ($expenses as $expense) {
                $expenseBalancer->apply($expense);
            }

            return $this->redirectToRoute('group_expenses', ['id' => $expense->getGroup()->getId()]);
        }

        return $this->render(
            'expense/updateExpense.html.twig',
            [
                'expense' => $expense,
                'form' => $form,
            ]
        );
    }
}
