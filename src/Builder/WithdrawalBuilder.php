<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Exception\OperationAccountAmountNotSufficient;
use Vinecave\B2BTask\Exception\OperationNotInitialized;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Model\Deposit;
use Vinecave\B2BTask\Model\Withdrawal;

class WithdrawalBuilder extends OperationBuilder
{
    /**
     * @throws OperationNotInitialized
     */
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


    /**
     * @throws OperationAccountAmountNotSufficient
     */
    public function begin(Account $account, int $amount): OperationBuilder
    {
        $accountAmount = $account->getAmount();
        $accountId = $account->getId();

        if ($account->getAmount() < $amount) {
            throw new OperationAccountAmountNotSufficient(
                "Account $accountId has $accountAmount is lesser then $amount"
            );
        }

        $this->setOperation(
            new Withdrawal($accountId, $amount)
        );

        return $this;
    }

    public static function getName(): string
    {
        return 'withdrawal';
    }

    /**
     * @throws OperationNotInitialized
     */
    protected function buildComment(): string
    {
        $operation = $this->getOperation();

        return "Withdrawal from account "
            ."{$operation->getAccountId()}, amount {$operation->getAmount()}";
    }
}
