<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Factory;

use Vinecave\B2BTask\Builder\DepositBuilder;
use Vinecave\B2BTask\Builder\OperationBuilder;
use Vinecave\B2BTask\Builder\TransferBuilder;
use Vinecave\B2BTask\Builder\WithdrawalBuilder;
use Exception;
use Vinecave\B2BTask\Exception\OperationNotFound;

class OperationBuilderFactory
{
    /**
     * @throws Exception
     */
    public function createBuilder(string $operation): OperationBuilder
    {
        $transactionFactory = new TransactionFactory();

        return match ($operation) {
            DepositBuilder::getName() => new DepositBuilder($transactionFactory),
            WithdrawalBuilder::getName() => new WithdrawalBuilder($transactionFactory),
            TransferBuilder::getName() => new TransferBuilder($transactionFactory),
            default => throw new OperationNotFound("Builder with name $operation is not found"),
        };
    }
}
