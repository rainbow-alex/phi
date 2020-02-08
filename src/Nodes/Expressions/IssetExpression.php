<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedIssetExpression;
use PhpParser\Node\Expr\Isset_;

class IssetExpression extends Expression
{
    use GeneratedIssetExpression;

    protected function extraValidation(int $flags): void
    {
        foreach ($this->expressions as $expression)
        {
            if ($expression->isTemporary())
            {
                throw ValidationException::invalidExpressionInContext($expression);
            }
        }
    }

    public function convertToPhpParserNode()
    {
        $expressions = [];
        foreach ($this->getExpressions() as $phiExpr)
        {
            $expressions[] = $phiExpr->convertToPhpParserNode();
        }
        return new Isset_($expressions);
    }
}
