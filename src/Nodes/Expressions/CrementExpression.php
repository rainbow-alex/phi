<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Token;

abstract class CrementExpression extends Expression
{
    abstract public function getExpression(): Expression;
    abstract public function getOperator(): Token;

    protected function extraValidation(int $flags): void
    {
        if ($this->getExpression()->isTemporary())
        {
            throw ValidationException::invalidExpressionInContext($this);
        }
    }
}
