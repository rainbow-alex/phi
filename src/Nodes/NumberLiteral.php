<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;

abstract class NumberLiteral extends Expression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }
    }
}
