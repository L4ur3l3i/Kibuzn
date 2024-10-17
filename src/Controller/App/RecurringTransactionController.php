<?php

namespace Kibuzn\Controller\App;

use Doctrine\ORM\EntityManagerInterface;
use Kibuzn\Entity\RecurringTransaction;
use Kibuzn\Form\RecurringTransactionType;
use Kibuzn\Repository\RecurringTransactionRepository;
use Kibuzn\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recurring-transaction')]
final class RecurringTransactionController extends AbstractController
{
    #[Route(name: 'app_recurring_transaction_index', methods: ['GET'])]
    public function index(RecurringTransactionRepository $recurringTransactionRepository, AccountService $accountService): Response
    {
        return $this->render('recurring_transaction/index.html.twig', [
            'recurring_transactions' => $recurringTransactionRepository->findBy(['account' => $accountService->getSelectedAccount()]),
        ]);
    }

    #[Route('/new', name: 'app_recurring_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AccountService $accountService): Response
    {
        $recurringTransaction = new RecurringTransaction();
        $form = $this->createForm(RecurringTransactionType::class, $recurringTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recurringTransaction->setAccount($accountService->getSelectedAccount());

            // Handle the end date
            if ($recurringTransaction->isPermanent()) {
                $recurringTransaction->setEndDate(null);
                $recurringTransaction->setIterations(null);
            }

            $entityManager->persist($recurringTransaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_recurring_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recurring_transaction/new.html.twig', [
            'recurring_transaction' => $recurringTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recurring_transaction_show', methods: ['GET'])]
    public function show(RecurringTransaction $recurringTransaction): Response
    {
        return $this->render('recurring_transaction/show.html.twig', [
            'recurring_transaction' => $recurringTransaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recurring_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecurringTransaction $recurringTransaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecurringTransactionType::class, $recurringTransaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the end date
            if ($recurringTransaction->isPermanent()) {
                $recurringTransaction->setEndDate(null);
                $recurringTransaction->setIterations(null);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('app_recurring_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recurring_transaction/edit.html.twig', [
            'recurring_transaction' => $recurringTransaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recurring_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, RecurringTransaction $recurringTransaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recurringTransaction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recurringTransaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recurring_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
