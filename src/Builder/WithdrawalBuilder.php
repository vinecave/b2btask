<?php

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Model\Deposit;
use Vinecave\B2BTask\Model\Withdrawal;

class WithdrawalBuilder extends OperationBuilder
{
    public function initTransactions(): OperationBuilder
    {
        $operation = $this->getOperation();

        $operation->addTransaction(
            $this->getTransactionFactory()->createTransaction(
                $operation->getAccountId(),
                - 1 * $operation->getAmount(),
                $this->getName(),
                $this->buildComment(),
            )
        );

        return $this;
    }


    public function begin(string $accountId, int $amount): OperationBuilder
    {
        $this->setOperation(
            new Withdrawal($accountId, $amount)
        );

        return $this;
    }

    public static function getName(): string
    {
        return 'withdrawal';
    }

    protected function buildComment(): string
    {
        $operation = $this->getOperation();

        return "Withdrawal from account "
            . "{$operation->getAccountId()}, amount {$operation->getAmount()}";
    }
}
