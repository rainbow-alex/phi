<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedYieldExpression;

// TODO yield without expression, by reference
class YieldExpression extends GeneratedYieldExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        if ($this->hasExpression())
        {
            $this->getExpression()->validateContext(self::CTX_READ); // TODO read or alias write depending on ref?
        }
    }
}
