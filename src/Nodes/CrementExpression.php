<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;

abstract class CrementExpression extends Expression
{
    abstract public function getExpression(): Expression;

    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        if (ExpressionClassification::isTemporary($this->getExpression()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        $this->getExpression()->validateContext(self::CTX_WRITE);
    }
}
