<?php

namespace Phi\Nodes\Context;

use Phi\Exception\ValidationException;

trait PureUnaryExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getExpression()->validateContext(self::CTX_READ);
    }
}
