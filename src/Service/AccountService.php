<?php

namespace Kibuzn\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;
use Kibuzn\Entity\Account;
use Symfony\Bundle\SecurityBundle\Security;

class AccountService
{
    private $requestStack;
    private $em;
    private $security;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, Security $security)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->security = $security;
    }

    public function getUserAccounts(): array
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (!$user) {
            return [];
        }

        return $user->getAccounts()->toArray();
    }

    public function getSelectedAccount()
    {
        $session = $this->requestStack->getSession();
        $selectedAccountId = $session->get('selected_account_id');

        if ($selectedAccountId) {
            return $this->em->getRepository(Account::class)->find($selectedAccountId);
        }

        $userAccounts = $this->getUserAccounts();

        // Get the account with the main flag set to true
        foreach ($userAccounts as $account) {
            if ($account->isMain()) {
                return $account;
            }
        }

        // Return the first account
        if (count($userAccounts) > 0) {
            return $userAccounts[0];
        }
    }

    public function setSelectedAccount(Account $account)
    {
        $session = $this->requestStack->getSession();
        $session->set('selected_account_id', $account->getId());
    }
}
