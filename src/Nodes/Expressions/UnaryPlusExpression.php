<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedUnaryPlusExpression;
use PhpParser\Node\Expr\UnaryPlus;

class UnaryPlusExpression extends Expression
{
    use GeneratedUnaryPlusExpression;

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
        return new UnaryPlus($this->getExpression()->convertToPhpParserNode());
    }
}
