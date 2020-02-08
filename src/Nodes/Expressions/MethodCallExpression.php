<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedMethodCallExpression;
use PhpParser\Node\Expr\MethodCall;

class MethodCallExpression extends Expression
{
    use GeneratedMethodCallExpression;

    protected function extraValidation(int $flags): void
    {
        if (!ExpressionClassification::isObjectAccessible($this->getObject()))
        {
            throw ValidationException::invalidSyntax($this->getOperator());
        }
    }

    public function convertToPhpParserNode()
    {
        return new MethodCall(
            $this->getObject()->convertToPhpParserNode(),
            $this->getName()->convertToPhpParserNode(),
            $this->getArguments()->convertToPhpParserNode()
        );
    }
}
