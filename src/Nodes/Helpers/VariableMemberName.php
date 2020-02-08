<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ArrayExpression;
use Phi\Nodes\Expressions\ExitExpression;
use Phi\Nodes\Expressions\NumberLiteral;
use Phi\Nodes\Expressions\PrintExpression;
use Phi\Nodes\Generated\GeneratedVariableMemberName;

class VariableMemberName extends MemberName
{
    use GeneratedVariableMemberName;

    protected function extraValidation(int $flags): void
    {
        $expression = $this->getExpression();
        if (
            $expression instanceof NumberLiteral
            || ($expression instanceof ArrayExpression && $expression->isConstant())
            || $expression instanceof ExitExpression
            || $expression instanceof PrintExpression
        )
        {
            throw ValidationException::invalidExpressionInContext($expression);
        }
    }

    public function convertToPhpParserNode()
    {
        return $this->getExpression()->convertToPhpParserNode();
    }
}
