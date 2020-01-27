<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedListExpression;

class ListExpression extends GeneratedListExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_READ|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        foreach ($this->getExpressions() as $expression)
        {
            $expression->validateContext(self::CTX_WRITE);
        }
    }
}
