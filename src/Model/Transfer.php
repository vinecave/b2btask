<?php

declare(strict_types=1);

namespace Vinecave\B2BTask\Model;

class Transfer extends Operation
{
    private ?string $targetAccountId = null;

    public function setTargetAccountId(string $targetAccountId): void
    {
        $this->targetAccountId = $targetAccountId;
    }

    public function getTargetAccountId(): ?string
    {
        return $this->targetAccountId;
    }
}
