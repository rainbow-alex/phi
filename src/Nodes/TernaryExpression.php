<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedTernaryExpression;

class TernaryExpression extends GeneratedTernaryExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getTest()->validateContext(self::CTX_READ);
        if ($then = $this->getThen())
        {
            $then->validateContext(self::CTX_READ);
        }
        $this->getElse()->validateContext(self::CTX_READ);
    }
}
