<?php

namespace Vinecave\B2BTask\Handler;

interface HandlerInterface
{
    public function handle(array $arguments);

    public static function getName(): string;
}
