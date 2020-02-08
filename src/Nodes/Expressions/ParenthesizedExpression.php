<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedParenthesizedExpression;
use Phi\Nodes\Helpers\Key;
use Phi\Nodes\Statements\ForeachStatement;

class ParenthesizedExpression extends Expression
{
    use GeneratedParenthesizedExpression;

    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    public function isTemporary(): bool
    {
        return $this->getExpression()->isTemporary();
    }

    protected function extraValidation(int $flags): void
    {
        if ($flags & self::CTX_WRITE)
        {
            $parent = $this->getParent();
            if (
                $parent instanceof ForeachStatement
                || ($parent instanceof Key && $parent->getParent() instanceof ForeachStatement)
                || $parent instanceof AssignmentExpression
                || $parent instanceof CombinedAssignmentExpression
                || $parent instanceof CrementExpression
            )
            {
                throw ValidationException::invalidExpressionInContext($this);
            }
        }

        parent::extraValidation($flags);
    }

    public function convertToPhpParserNode()
    {
        return $this->getExpression()->convertToPhpParserNode();
    }
}
