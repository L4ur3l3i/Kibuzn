<?php

namespace Kibuzn\Controller\App;

use Doctrine\ORM\EntityManagerInterface;
use Kibuzn\Entity\Account;
use Kibuzn\Form\AccountType;
use Kibuzn\Entity\User;
use Kibuzn\Repository\AccountRepository;
use Kibuzn\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account')]
final class AccountController extends AbstractController
{
    #[Route(name: 'app_account_index', methods: ['GET'])]
    public function index(AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'accounts' => $accountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_account_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $this->getUser();
            $account->addUser($user);

            // Set the account as the default account if it is the first account created by the current user
            $account->setMain($user->getAccounts()->isEmpty());

            // Set the name of the account to the bank brand by default
            $account->setName($account->getBank()->getBrand());

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_show', methods: ['GET'])]
    public function show(Account $account): Response
    {
        return $this->render('account/show.html.twig', [
            'account' => $account,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccountType::class, $account, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Check on 'main' field so that only one account can be the main account
            /** @var User $user */
            $user = $this->getUser();

            if ($account->isMain()) {
                foreach ($user->getAccounts() as $userAccount) {
                    if ($userAccount->isMain() && $userAccount->getId() !== $account->getId()) {
                        $userAccount->setMain(false);
                        $entityManager->persist($userAccount);
                    }
                }
            } else {
                if ($user->getAccounts()->filter(fn(Account $userAccount) => $userAccount->isMain())->isEmpty()) {
                    $account->setMain(true);
                }
            }

            // Save the changes
            $entityManager->flush();

            return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, Account $account, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $account->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_account_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/set-selected-account/{id}', name: 'api_selected_account', methods: ['POST'])]
    public function setSelectedAccount(Account $account, AccountService $accountService): JsonResponse
    {
        if ($account) {
            $accountService->setSelectedAccount($account);
            return new JsonResponse(['success' => true]);
        }

        return new JsonResponse(['success' => false], 400);
    }
}
