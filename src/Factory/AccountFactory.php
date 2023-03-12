<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Factory;

use Vinecave\B2BTask\Model\Account;

class AccountFactory
{
    public function createAccount(string $accountId): Account
    {
        return new Account($accountId);
    }
}
