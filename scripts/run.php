<?php

use Vinecave\B2BTask\Factory\AccountFactory;
use Vinecave\B2BTask\Factory\OperationBuilderFactory;
use Vinecave\B2BTask\Factory\TransactionFactory;
use Vinecave\B2BTask\Handler\GetAccountsHandler;
use Vinecave\B2BTask\Handler\GetAccountHandler;
use Vinecave\B2BTask\Handler\GetTransactionsHandler;
use Vinecave\B2BTask\Handler\MakeOperationHandler;
use Vinecave\B2BTask\Repository\TransactionRepository;
use Vinecave\B2BTask\Storage\Storage;

$handlerName = $argv[0];
$storage = new Storage('/app/data/log.csv');
$transactionFactory = new TransactionFactory();
$accountFactory = new AccountFactory();
$operationBuilderFactory = new OperationBuilderFactory();
$transactionRepository = new TransactionRepository($transactionFactory, $storage);

$handler = match($handlerName) {
    GetTransactionsHandler::getName() => new GetTransactionsHandler($transactionRepository),
    GetAccountHandler::getName() => new GetAccountHandler($transactionRepository, $accountFactory),
    GetAccountsHandler::getName() => new GetAccountsHandler($transactionRepository, $accountFactory),
    MakeOperationHandler::getName() => new MakeOperationHandler($operationBuilderFactory, $transactionRepository),
};

try {
    print_r($handler->handle($argv));
} catch (Exception $e) {
    print_r('Error: ' . $e->getMessage());
}
