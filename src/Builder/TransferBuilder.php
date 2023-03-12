<?php

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Exception\OperationAccountAmountNotSufficient;
use Vinecave\B2BTask\Exception\OperationEmptyTransferAccount;
use Vinecave\B2BTask\Exception\OperationTargetAccountNotSet;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Model\Transfer;
use Exception;

class TransferBuilder extends OperationBuilder
{
    /**
     * @throws OperationTargetAccountNotSet
     * @throws Exception
     */
    public function initTransactions(): OperationBuilder
    {
        $operation = $this->getOperation();

        if ($operation->getTargetAccountId() == null) {
            throw new OperationTargetAccountNotSet('Target account is not set');
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

        $this->setOperation(new Transfer($account->getId(), $amount));

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setOptions(array $options): self
    {
        $targetAccountId = $options['target_accountId'] ?? null;

        if ($targetAccountId == null) {
            throw new OperationEmptyTransferAccount('Empty target account id');
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

    /**
     * @throws Exception
     */
    protected function buildComment(): string
    {
        $operation = $this->getOperation();

        return "Transfer from account "
            ."{$operation->getAccountId()}, "
            ."into account {$operation->getTargetAccountId()}, "
            ."amount {$operation->getAmount()}";
    }
}
