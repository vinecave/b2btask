<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Handler;

use League\Csv\Exception;
use Vinecave\B2BTask\Exception\NoTransactionsForAccountFound;
use Vinecave\B2BTask\Exception\TransactionFieldIsNotFound;
use Vinecave\B2BTask\Repository\TransactionRepository;

class GetTransactionsHandler implements HandlerInterface
{
    private const ASC = 'ASC';
    private const DESC = 'DESC';

    public function __construct(
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    /**
     * @throws NoTransactionsForAccountFound
     * @throws TransactionFieldIsNotFound
     */
    public function handle(array $arguments): array
    {
        $accountId = $arguments[2];
        $sortBy = $arguments[3] ?? 'amount';
        $sortOrder = $arguments[4] ?? self::ASC;

        $sortOrderNumber = match ($sortOrder) {
            self::DESC => SORT_DESC,
            default => SORT_ASC
        };

        $result = $this->transactionRepository->findTransactions($accountId, $sortBy, $sortOrderNumber);

        if (empty($result)) {
            throw new NoTransactionsForAccountFound("No transactions found for $accountId");
        }

        return $result;
    }

    public static function getName(): string
    {
        return 'getTransactions';
    }
}
