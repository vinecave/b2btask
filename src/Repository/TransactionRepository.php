<?php

namespace Vinecave\B2BTask\Repository;

use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Vinecave\B2BTask\Factory\TransactionFactory;
use Vinecave\B2BTask\Model\Transaction;
use Vinecave\B2BTask\Storage\Storage;

class TransactionRepository
{
    public function __construct(
        private readonly TransactionFactory $transactionFactory,
        private readonly Storage $storage
    ) {
    }

    /**
     * @param string $accountId
     * @param string $sortBy
     * @param string $sortOrder
     * @return Transaction[]
     * @throws Exception
     */
    public function findTransactions(string $accountId, string $sortBy = 'amount', string $sortOrder = 'ASC'): array
    {
        $rows = $this->storage->readRows();
        $result = [];
        $sorting = [];

        foreach ($rows as $row) {
            if ($row['account_id'] === $accountId) {
                $result[] = $this->transactionFactory->createTransaction(
                    $row['account_id'],
                    $row['amount'],
                    $row['operation'],
                    $row['comment'],
                    strtotime($row['due_date'])
                );

                $sorting[] = $row[$sortBy] ?? null;
            }
        }

        array_multisort($sorting, $result);

        return $result;
    }

    /**
     * @return Transaction[]
     */
    public function findAllTransactions(): array
    {
        $rows = $this->storage->readRows();

        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->transactionFactory->createTransaction(
                $row['account_id'],
                $row['amount'],
                $row['operation'],
                $row['comment'],
                strtotime($row['due_date'])
            );
        }

        return $result;
    }

    /**
     * @throws CannotInsertRecord
     */
    public function saveTransaction(Transaction $transaction): void
    {
        $this->storage->insertRow(
            [
                'due_date' => $transaction->getDueDate()->format('Y-m-d H:i:s'),
                'operation' => $transaction->getOperation(),
                'account_id' => $transaction->getAccountId(),
                'comment' => $transaction->getComment(),
                'amount' => $transaction->getAmount()
            ]
        );
    }
}
