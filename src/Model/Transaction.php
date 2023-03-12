<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Model;

use DateTime;

class Transaction
{
    public function __construct(
        private readonly string $accountId,
        private readonly int $amount,
        private readonly string $operation,
        private readonly string $comment,
        private readonly DateTime $dueDate,
    ) {
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }
}
