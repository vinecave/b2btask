<?php

namespace Vinecave\B2BTask\Handler;

use Vinecave\B2BTask\Factory\OperationBuilderFactory;
use Exception;
use Vinecave\B2BTask\Repository\TransactionRepository;

class MakeOperationHandler implements HandlerInterface
{
    public function __construct(
        private readonly OperationBuilderFactory $operationBuilderFactory,
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    /**
     * @param array $arguments
     * @return void
     * @throws Exception
     */
    public function handle(array $arguments): void
    {
        $operation = $arguments[2];
        $accountId = $arguments[3];
        $amount = $arguments[4];
        $targetAccountId = $arguments[5] ?? null;

        $operationBuilder = $this->operationBuilderFactory->createBuilder($operation);

        $operationBuilder->begin($accountId, $amount);

        if ($targetAccountId) {
            $operationBuilder->setOptions(['target_account_id' => $targetAccountId]);
        }

        $operation = $operationBuilder->initTransactions()->commit();

        foreach ($operation->getTransactions() as $transaction) {
            $this->transactionRepository->saveTransaction($transaction);
        }
    }

    public static function getName(): string
    {
        return 'makeOperation';
    }
}
