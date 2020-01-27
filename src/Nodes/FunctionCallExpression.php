<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedFunctionCallExpression;

class FunctionCallExpression extends GeneratedFunctionCallExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS_WRITE;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getCallee()->validateContext(self::CTX_READ);

        if (!ExpressionClassification::isCallable($this->getCallee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        foreach ($this->getArguments() as $argument)
        {
            $argument->validateContext();
        }
    }
}
