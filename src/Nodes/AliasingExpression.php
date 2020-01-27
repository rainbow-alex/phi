<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedAliasingExpression;

class AliasingExpression extends GeneratedAliasingExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getAlias()->validateContext(self::CTX_ALIAS_WRITE);
        $this->getValue()->validateContext(self::CTX_ALIAS_READ);
    }
}
