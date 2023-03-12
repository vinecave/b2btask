<?php

namespace Vinecave\B2BTask\Handler;

use League\Csv\Exception;
use Vinecave\B2BTask\Factory\AccountFactory;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Repository\TransactionRepository;

class GetAccountHandler implements HandlerInterface
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountFactory $accountFactory
    ) {
    }

    /**
     * @throws Exception
     */
    public function handle(array $arguments): Account
    {
        $accountId = $arguments[2];

        $account = $this->accountFactory->createAccount($accountId);

        $transactions = $this->transactionRepository->findTransactions($accountId);

        foreach ($transactions as $transaction) {
            $account->addAmount($transaction->getAmount());
        }

        return $account;
    }

    public static function getName(): string
    {
        return 'getAccount';
    }
}
