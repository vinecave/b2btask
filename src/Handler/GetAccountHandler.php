<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Handler;

use League\Csv\Exception;
use Vinecave\B2BTask\Exception\AccountNotFound;
use Vinecave\B2BTask\Exception\TransactionFieldIsNotFound;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Service\AccountService;

class GetAccountHandler implements HandlerInterface
{
    public function __construct(
        private readonly AccountService $accountService
    ) {
    }

    /**
     * @throws AccountNotFound
     * @throws TransactionFieldIsNotFound
     */
    public function handle(array $arguments): Account
    {
        $accountId = $arguments[2];

        return $this->accountService->findAccount($accountId);
    }

    public static function getName(): string
    {
        return 'getAccount';
    }
}
