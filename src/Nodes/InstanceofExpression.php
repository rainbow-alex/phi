<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedInstanceofExpression;

class InstanceofExpression extends GeneratedInstanceofExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getValue()->validateContext(self::CTX_READ);

        if (!ExpressionClassification::isObject($this->getValue()))
        {
            throw new ValidationException(__METHOD__, $this);
        }

        $this->getType()->validateContext(self::CTX_READ);

        if (!ExpressionClassification::isNewable($this->getType()))
        {
            throw new ValidationException(__METHOD__, $this);
        }
    }
}
