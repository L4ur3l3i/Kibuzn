<?php

namespace Kibuzn\Twig;

use Kibuzn\Service\AccountService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AccountExtension extends AbstractExtension implements GlobalsInterface
{
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getGlobals(): array
    {
        return [
            'user_accounts' => $this->accountService->getUserAccounts(),
            'selected_account' => $this->accountService->getSelectedAccount(),
        ];
    }
}
