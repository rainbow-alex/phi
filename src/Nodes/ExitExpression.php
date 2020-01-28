<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedExitExpression;

class ExitExpression extends GeneratedExitExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        if ($expression = $this->getExpression())
        {
            $expression->validateContext(self::CTX_READ);
        }
    }
}
