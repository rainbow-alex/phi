<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedStaticMethodCallExpression;

class StaticMethodCallExpression extends GeneratedStaticMethodCallExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS_WRITE;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getClass()->validateContext(self::CTX_READ);

        // TODO member

        foreach ($this->getArguments() as $argument)
        {
            $argument->validateContext();
        }
    }
}
