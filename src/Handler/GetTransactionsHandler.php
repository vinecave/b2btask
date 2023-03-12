<?php

namespace Vinecave\B2BTask\Handler;

use League\Csv\Exception;
use Vinecave\B2BTask\Repository\TransactionRepository;

class GetTransactionsHandler implements HandlerInterface
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(array $arguments): array
    {
        $accountId = $arguments[2];
        $sortBy = $arguments[3];
        $sortOrder = $arguments[4];

        return $this->transactionRepository->findTransactions($accountId, $sortBy, $sortOrder);
    }

    public static function getName(): string
    {
        return 'getTransactions';
    }
}
