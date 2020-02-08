<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedUnaryMinusExpression;
use PhpParser\Node\Expr\UnaryMinus;

class UnaryMinusExpression extends Expression
{
    use GeneratedUnaryMinusExpression;

    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    protected function extraValidation(int $flags): void
    {
        $expression = $this->getExpression();
        if ($expression instanceof ArrayExpression && $expression->isConstant())
        {
            throw ValidationException::invalidSyntax($expression);
        }
    }

    public function convertToPhpParserNode()
    {
        return new UnaryMinus($this->getExpression()->convertToPhpParserNode());
    }
}
