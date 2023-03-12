<?php

namespace Vinecave\B2BTask\Model;

abstract class Operation
{
    /**
     * @var Transaction[]
     */
    private array $transactions;

    public function __construct(
        private readonly string $accountId,
        private readonly int $amount
    ) {
    }

    public function addTransaction(Transaction $transaction): void
    {
        $this->transactions[] = $transaction;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
