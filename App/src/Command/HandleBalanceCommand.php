<?php

namespace App\Command;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:handle-balance')]
class HandleBalanceCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExpenseRepository $expenseRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Handle balance of the group.')
            ->setHelp('This command allows you to handle the balance of a group...');
        // Set un argument qui va récupérer le nom d'un groupe fictif
        // Set un argument qui va récupérer les dépenses de tout le groupe
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // $expense = new Expense(36, "Camille", ["Alice", "Charles", "Camille"], "Essence");
        // $this->entityManager->persist($expense);
        // $this->entityManager->flush();

        $expenses = $this->expenseRepository->findAll();
        foreach ($expenses as $expense) {

            $modulo = ($expense->getAmount()) % ($expense->getAmountByParticipant());

            $amount = $expense->getAmount();
            $amountByParticipant = $expense->getAmountByParticipant();
            $participantNb = count($expense->getParticipants());

            echo (sprintf(
                "%s a payé %s€ (%s€ par participant) (%s)",
                $expense->getPayer(),
                $expense->getAmount(),
                $expense->getAmountByParticipant(),
                $expense->getDescription()
            ) . PHP_EOL);

            // echo (sprintf(
            //     "%s doit %s au groupe",

            // ) . PHP_EOL);
        }


        // $output->writeln([
        //     'Groupe Week-end entre amis !',
        //     '============',
        //     "Ici s'integrera le calcul de la balance du groupe",
        // ]);

        return Command::SUCCESS;
    }
}
