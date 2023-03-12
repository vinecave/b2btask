<?php

namespace Vinecave\B2BTask\Factory;

use Vinecave\B2BTask\Model\Transaction;
use DateTime;

class TransactionFactory
{
    public function createTransaction(
        string $accountId,
        int $amount,
        string $operation,
        string $comment,
        int $timestamp = null
    ): Transaction {
        if ($timestamp === null) {
            $dueDate = new DateTime('@'.time());
        } else {
            $dueDate = new DateTime('@'.$timestamp);
        }

        return new Transaction(
            $accountId,
            $amount,
            $operation,
            $comment,
            $dueDate,
        );
    }
}
