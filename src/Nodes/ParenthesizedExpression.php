<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedParenthesizedExpression;

class ParenthesizedExpression extends GeneratedParenthesizedExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        // note: this is one of the very few cases where flags are passed as they are
        // instead of explicitely specifying a new context
        /** @see Expression::CTX_READ_IMPLICIT_BY_REF */
        $this->getExpression()->validateContext($flags);
    }
}
