<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Builder;

use Vinecave\B2BTask\Exception\OperationBuilderOptionsNotImplemented;
use Vinecave\B2BTask\Exception\OperationNotInitialized;
use Vinecave\B2BTask\Factory\TransactionFactory;
use Vinecave\B2BTask\Model\Account;
use Vinecave\B2BTask\Model\Operation;
use Exception;

abstract class OperationBuilder
{
    private ?Operation $operation;

    private TransactionFactory $transactionFactory;

    public function __construct(TransactionFactory $transactionFactory)
    {
        $this->transactionFactory = $transactionFactory;
    }

    protected function getTransactionFactory(): TransactionFactory
    {
        return $this->transactionFactory;
    }

    abstract public function begin(Account $account, int $amount): self;

    abstract public function initTransactions(): self;

    /**
     * @throws Exception
     */
    public function commit(): Operation
    {
        if ($this->operation == null) {
            throw new OperationNotInitialized("Operation is not initialized, call ".$this::class."->begin");
        }

        $operation = $this->operation;
        $this->operation = null;
        return $operation;
    }

    abstract public static function getName(): string;

    abstract protected function buildComment(): string;

    /**
     * @throws OperationNotInitialized
     */
    protected function getOperation(): Operation
    {
        if ($this->operation == null) {
            throw new OperationNotInitialized("Operation is not initialized, call ".$this::class."->begin");
        }

        return $this->operation;
    }

    protected function setOperation(Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setOptions(array $options): self
    {
        throw new OperationBuilderOptionsNotImplemented('This operation builder does not have options');
    }
}
