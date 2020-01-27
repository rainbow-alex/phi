<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedNewExpression;

class NewExpression extends GeneratedNewExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getClass()->validateContext(self::CTX_READ);

        if (!ExpressionClassification::isNewable($this->getClass()))
        {
            throw new ValidationException(__METHOD__, $this);
        }

        foreach ($this->getArguments() as $argument)
        {
            $argument->validateContext();
        }
    }
}
