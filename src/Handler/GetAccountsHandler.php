<?php

namespace Vinecave\B2BTask\Handler;

use Vinecave\B2BTask\Factory\AccountFactory;
use Vinecave\B2BTask\Repository\TransactionRepository;

class GetAccountsHandler implements HandlerInterface
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountFactory $accountFactory
    ) {
    }


    public function handle(array $arguments): array
    {
        $result = [];

        $transactions = $this->transactionRepository->findAllTransactions();

        foreach ($transactions as $transaction) {
            $account = $result[$transaction->getAccountId()] ?? null;

            if ($account === null) {
                $account = $this->accountFactory->createAccount($transaction->getAccountId());
                $result[$account->getId()] = $account;
            }
        }

        return $result;
    }

    public static function getName(): string
    {
        return 'getAccounts';
    }
}
