<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Handler;

use Vinecave\B2BTask\Exception\AccountNotFound;
use Vinecave\B2BTask\Exception\InvalidAccountId;
use Vinecave\B2BTask\Exception\TransactionFieldIsNotFound;
use Vinecave\B2BTask\Factory\OperationBuilderFactory;
use Exception;
use Vinecave\B2BTask\Repository\TransactionRepository;
use Vinecave\B2BTask\Service\AccountService;

class MakeOperationHandler implements HandlerInterface
{
    public function __construct(
        private readonly OperationBuilderFactory $operationBuilderFactory,
        private readonly AccountService $accountService,
        private readonly TransactionRepository $transactionRepository
    ) {
    }

    /**
     * @param array $arguments
     * @return void
     * @throws InvalidAccountId
     * @throws TransactionFieldIsNotFound
     * @throws Exception
     */
    public function handle(array $arguments): void
    {
        $operation = $arguments[2];
        $accountId = $arguments[3];
        $amount = (int) $arguments[4];
        $targetAccountId = $arguments[5] ?? null;

        $operationBuilder = $this->operationBuilderFactory->createBuilder($operation);

        try {
            $account = $this->accountService->findAccount($accountId);
        } catch (AccountNotFound $exception) {
            $account = $this->accountService->createAccount($accountId);
        }

        $operationBuilder->begin($account, $amount);

        if ($targetAccountId) {
            $transferAccount = $this->accountService->findAccount($targetAccountId);
            $operationBuilder->setOptions(['target_accountId' => $transferAccount->getId()]);
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
