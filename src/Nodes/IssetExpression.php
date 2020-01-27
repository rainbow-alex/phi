<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedIssetExpression;

class IssetExpression extends GeneratedIssetExpression
{
    public function validateContext(int $flags): void
    {
        foreach ($this->getExpressions() as $expression)
        {
            $expression->validateContext(self::CTX_READ);

            if (ExpressionClassification::isTemporary($expression))
            {
                throw new ValidationException(__METHOD__, $this); // TODO
            }
        }
    }
}
