<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedSuppressErrorsExpression;

// TODO can this be used in write at all?
class SuppressErrorsExpression extends GeneratedSuppressErrorsExpression
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
