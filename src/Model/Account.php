<?php

namespace Vinecave\B2BTask\Model;

class Account
{
    private int $amount = 0;

    public function __construct(
        private readonly string $id
    ) {
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function addAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
