<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Repository;

use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use Vinecave\B2BTask\Exception\TransactionFieldIsNotFound;
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
     * @param int $sortOrder
     * @return Transaction[]
     * @throws TransactionFieldIsNotFound
     */
    public function findTransactions(string $accountId, string $sortBy = 'amount', int $sortOrder = SORT_ASC): array
    {
        $rows = $this->storage->readRows();
        $result = [];
        $sorting = [];
        $sortType = SORT_NUMERIC;

        foreach ($rows as $row) {
            if ($row['accountId'] === $accountId) {
                $result[] = $this->transactionFactory->createTransaction(
                    $row['accountId'],
                    (int) $row['amount'],
                    $row['operation'],
                    $row['comment'],
                    strtotime($row['dueDate'])
                );

                if (empty($row[$sortBy])) {
                    throw new TransactionFieldIsNotFound("Field $sortBy is not found in row");
                }

                if (is_numeric($row[$sortBy])) {
                    $sortType = SORT_NUMERIC;
                } else {
                    $sortType = SORT_STRING;
                }

                $sorting[] = $row[$sortBy];
            }
        }

        array_multisort($sorting, $sortOrder, $sortType, $result);

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
                $row['accountId'],
                (int) $row['amount'],
                $row['operation'],
                $row['comment'],
                strtotime($row['dueDate'])
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
                'dueDate' => $transaction->getDueDate()->format('Y-m-d H:i:s'),
                'operation' => $transaction->getOperation(),
                'accountId' => $transaction->getAccountId(),
                'comment' => $transaction->getComment(),
                'amount' => $transaction->getAmount()
            ]
        );
    }
}
