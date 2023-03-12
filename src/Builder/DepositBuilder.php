<?php

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Model\Deposit;

class DepositBuilder extends OperationBuilder
{
    public function initTransactions(): OperationBuilder
    {
        $operation = $this->getOperation();

        $operation->addTransaction(
            $this->getTransactionFactory()->createTransaction(
                $operation->getAccountId(),
                $operation->getAmount(),
                $this->getName(),
                $this->buildComment()
            )
        );

        return $this;
    }


    public function begin(string $accountId, int $amount): OperationBuilder
    {
        $this->setOperation(new Deposit($accountId, $amount));

        return $this;
    }

    public static function getName(): string
    {
        return 'deposit';
    }

    protected function buildComment(): string
    {
        $operation = $this->getOperation();

        return "Deposit for account "
            ."{$operation->getAccountId()}, amount {$operation->getAmount()}";
    }
}
