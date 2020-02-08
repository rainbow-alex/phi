<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedYieldExpression;
use Phi\Nodes\Oop\Method;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statements\FunctionStatement;

class YieldExpression extends Expression
{
    use GeneratedYieldExpression;

    protected function extraValidation(int $flags): void
    {
        $key = $this->getKey();
        if ($key && $key->getExpression()->getPrecedence() <= $this->getPrecedence())
        {
            throw ValidationException::badPrecedence($key->getExpression());
        }

        $expression = $this->getExpression();
        if ($expression && $expression->getPrecedence() < $this->getPrecedence())
        {
            throw ValidationException::badPrecedence($expression);
        }

        for ($parent = $this->getParent(); $parent; $parent = $parent->getParent())
        {
            if (
                $parent instanceof FunctionStatement
                || $parent instanceof Method
                || $parent instanceof AnonymousFunctionExpression
            )
            {
                break;
            }
            else if ($parent instanceof RootNode)
            {
                throw ValidationException::invalidExpressionInContext($this);
            }
        }
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_YIELD;
    }
}
