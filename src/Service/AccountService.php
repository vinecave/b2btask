<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Service;

use Vinecave\B2BTask\Exception\AccountNotFound;
use Vinecave\B2BTask\Exception\InvalidAccountId;
use Vinecave\B2BTask\Exception\NoTransactionsFound;
use Vinecave\B2BTask\Exception\TransactionFieldIsNotFound;
use Vinecave\B2BTask\Factory\AccountFactory;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Repository\TransactionRepository;

class AccountService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly AccountFactory $accountFactory
    ) {
    }

    /**
     * @throws AccountNotFound
     * @throws TransactionFieldIsNotFound
     */
    public function findAccount(string $accountId): Account
    {
        $transactions = $this->transactionRepository->findTransactions($accountId);

        if (empty($transactions)) {
            throw new AccountNotFound("Account $accountId not found");
        }

        $account = $this->accountFactory->createAccount($accountId);

        foreach ($transactions as $transaction) {
            $account->addAmount($transaction->getAmount());
        }

        return $account;
    }

    /**
     * @return Account[]
     * @throws NoTransactionsFound
     */
    public function findAllAccounts(): array
    {
        $transactions = $this->transactionRepository->findAllTransactions();
        $result = [];

        if (empty($transactions)) {
            throw new NoTransactionsFound('No transactions found, check data directory');
        }

        foreach ($transactions as $transaction) {
            $account = $result[$transaction->getAccountId()] ?? null;

            if ($account === null) {
                $account = $this->accountFactory->createAccount($transaction->getAccountId());
                $result[$account->getId()] = $account;
            }

            $account->addAmount($transaction->getAmount());
        }

        return $result;
    }

    /**
     * @throws InvalidAccountId
     */
    public function createAccount(string $accountId): Account
    {
        if (false === ctype_alnum($accountId)) {
            throw new InvalidAccountId('Invalid Account Id, only alphanumeric string allowed');
        }

        return $this->accountFactory->createAccount($accountId);
    }
}
