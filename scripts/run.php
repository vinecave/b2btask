<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Vinecave\B2BTask\Factory\AccountFactory;
use Vinecave\B2BTask\Factory\OperationBuilderFactory;
use Vinecave\B2BTask\Factory\TransactionFactory;
use Vinecave\B2BTask\Handler\GetAccountsHandler;
use Vinecave\B2BTask\Handler\GetAccountHandler;
use Vinecave\B2BTask\Handler\GetTransactionsHandler;
use Vinecave\B2BTask\Handler\MakeOperationHandler;
use Vinecave\B2BTask\Repository\TransactionRepository;
use Vinecave\B2BTask\Service\AccountService;
use Vinecave\B2BTask\Storage\Storage;

$dataFile = 'data/log.csv';

if (false === file_exists($dataFile)) {
    touch($dataFile);
}

$handlerName = $argv[1];
$storage = new Storage($dataFile);
$transactionFactory = new TransactionFactory();
$accountFactory = new AccountFactory();
$operationBuilderFactory = new OperationBuilderFactory();
$transactionRepository = new TransactionRepository($transactionFactory, $storage);
$accountService = new AccountService($transactionRepository, $accountFactory);

$handler = match ($handlerName) {
    GetTransactionsHandler::getName() => new GetTransactionsHandler($transactionRepository),
    GetAccountHandler::getName() => new GetAccountHandler($accountService),
    GetAccountsHandler::getName() => new GetAccountsHandler($accountService),
    MakeOperationHandler::getName() => new MakeOperationHandler(
        $operationBuilderFactory,
        $accountService,
        $transactionRepository
    ),
};


print_r($handler->handle($argv));
