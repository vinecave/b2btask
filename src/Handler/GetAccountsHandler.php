<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Handler;

use Vinecave\B2BTask\Exception\NoTransactionsFound;
use Vinecave\B2BTask\Service\AccountService;

class GetAccountsHandler implements HandlerInterface
{
    public function __construct(
        private readonly AccountService $accountService
    ) {
    }


    /**
     * @throws NoTransactionsFound
     */
    public function handle(array $arguments): array
    {
        return $this->accountService->findAllAccounts();
    }

    public static function getName(): string
    {
        return 'getAccounts';
    }
}
