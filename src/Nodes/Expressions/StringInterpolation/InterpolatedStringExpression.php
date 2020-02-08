<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Expressions\FunctionCallExpression;
use Phi\Nodes\Expressions\MethodCallExpression;
use Phi\Nodes\Expressions\PropertyAccessExpression;
use Phi\Nodes\Expressions\StaticMethodCallExpression;
use Phi\Nodes\Expressions\StaticPropertyAccessExpression;
use Phi\Nodes\Expressions\VariableExpression;
use Phi\Nodes\Generated\GeneratedInterpolatedStringExpression;

class InterpolatedStringExpression extends InterpolatedStringPart
{
    use GeneratedInterpolatedStringExpression;

    protected function extraValidation(int $flags): void
    {
        $expression = $this->getExpression();
        if (!(
            $expression instanceof VariableExpression
            || $expression instanceof ArrayAccessExpression
            || $expression instanceof PropertyAccessExpression
            || $expression instanceof FunctionCallExpression
            || $expression instanceof MethodCallExpression
            || $expression instanceof StaticPropertyAccessExpression
            || $expression instanceof StaticMethodCallExpression
            // TODO: via ast manipulation a bunch more invalid combinations can be created, validate
        ))
        {
            throw ValidationException::invalidExpressionInContext($expression);
        }
    }

    public function convertToPhpParserNode()
    {
        return $this->getExpression()->convertToPhpParserNode();
    }
}
