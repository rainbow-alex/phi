<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedBitwiseNotExpression;
use PhpParser\Node\Expr\BitwiseNot;

class BitwiseNotExpression extends Expression
{
    use GeneratedBitwiseNotExpression;

    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    protected function extraValidation(int $flags): void
    {
        $expression = $this->getExpression();
        if (
            ($expression instanceof ArrayExpression && $expression->isConstant())
            || $expression instanceof ExitExpression // not the case for unary - and + ...
        )
        {
            throw ValidationException::invalidSyntax($expression);
        }
    }

    public function convertToPhpParserNode()
    {
        return new BitwiseNot($this->getExpression()->convertToPhpParserNode());
    }
}
