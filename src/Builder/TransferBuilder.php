<?php

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Model\Transfer;
use Exception;

class TransferBuilder extends OperationBuilder
{
    public function initTransactions(): OperationBuilder
    {
        $operation = $this->getOperation();

        if ($operation->getTargetAccountId() == null) {
            throw new Exception('Target account is not set');
        }

        $operation->addTransaction(
            $this->getTransactionFactory()->createTransaction(
                $operation->getAccountId(),
                - 1 * $operation->getAmount(),
                $this->getName(),
                $this->buildComment(),
                time()
            )
        );

        $operation->addTransaction(
            $this->getTransactionFactory()->createTransaction(
                $operation->getTargetAccountId(),
                $operation->getAmount(),
                $this->getName(),
                $this->buildComment()
            )
        );

        return $this;
    }


    public function begin(string $accountId, int $amount): OperationBuilder
    {
        $this->setOperation(new Transfer($accountId, $amount));

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setOptions(array $options): self
    {
        $targetAccountId = $options['target_account_id'] ?? null;

        if ($targetAccountId == null) {
            throw new Exception('Empty target account id');
        }

        $this->getOperation()->setTargetAccountId($targetAccountId);

        return $this;
    }

    /**
     * @throws Exception
     */
    protected function getOperation(): Transfer
    {
        /** @var Transfer $operation */
        $operation = parent::getOperation();

        return $operation;
    }

    public static function getName(): string
    {
        return 'transfer';
    }

    protected function buildComment(): string
    {
        $operation = $this->getOperation();

        return "Transfer from account "
            . "{$operation->getAccountId()}, "
            . "into account {$operation->getTargetAccountId()}, "
            . "amount {$operation->getAmount()}";
    }
}
