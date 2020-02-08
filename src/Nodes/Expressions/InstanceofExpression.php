<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedInstanceofExpression;
use PhpParser\Node\Expr\Instanceof_;

class InstanceofExpression extends Expression
{
    use GeneratedInstanceofExpression;

    protected function extraValidation(int $flags): void
    {
        if (
            $this->getExpression() instanceof ClassNameResolutionExpression
            || $this->getExpression() instanceof ExitExpression
            || $this->getExpression() instanceof MagicConstant
            || $this->getExpression() instanceof StringLiteral
            || $this->getExpression() instanceof NumberLiteral
            || ($this->getExpression() instanceof ArrayExpression && $this->getExpression()->isConstant())
        )
        {
            throw ValidationException::invalidExpressionInContext($this->getExpression());
        }

        if (!ExpressionClassification::isNewable($this->getClass()))
        {
            throw ValidationException::invalidExpressionInContext($this->getClass());
        }
    }

    public function convertToPhpParserNode()
    {
        $class = $this->getClass();
        if ($class instanceof NameExpression)
        {
            $class = $class->getName();
        }
        return new Instanceof_($this->getExpression()->convertToPhpParserNode(), $class->convertToPhpParserNode());
    }
}
