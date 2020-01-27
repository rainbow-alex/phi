<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedMethodCallExpression;

class MethodCallExpression extends GeneratedMethodCallExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS_WRITE;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getObject()->validateContext(self::CTX_READ);
        // TODO member
        foreach ($this->getArguments() as $argument)
        {
            $argument->validateContext();
        }
    }
}
