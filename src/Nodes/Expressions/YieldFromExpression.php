<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedYieldFromExpression;
use Phi\Nodes\Oop\Method;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statements\FunctionStatement;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;

class YieldFromExpression extends Expression
{
    use GeneratedYieldFromExpression;
    use UnaryOpExpression { extraValidation as validatePrecedence; }

    protected function extraValidation(int $flags): void
    {
        $this->validatePrecedence($flags);

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
