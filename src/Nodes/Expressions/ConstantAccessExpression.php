<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedConstantAccessExpression;
use PhpParser\Node\Expr\ClassConstFetch;

class ConstantAccessExpression extends Expression
{
    use GeneratedConstantAccessExpression;

    public function isConstant(): bool
    {
        return $this->getClass()->isConstant();
    }

    protected function extraValidation(int $flags): void
    {
        $class = $this->getClass();
        if (
            $class instanceof NewExpression
            || ($class instanceof ArrayExpression && $class->isConstant())
            || $class instanceof ClassNameResolutionExpression
            || $class instanceof ConstantAccessExpression
            || $class instanceof MagicConstant // check these in other contexts
            || ($class instanceof ParenthesizedExpression && $class->isConstant())
            || $class instanceof ExitExpression
            || $class instanceof EmptyExpression
            || $class instanceof EvalExpression
            || $class instanceof ExecExpression
            || $class instanceof IssetExpression
            || $class instanceof AnonymousFunctionExpression
            || $class instanceof NumberLiteral
        )
        {
            throw ValidationException::invalidSyntax($class);
        }
    }

    public function convertToPhpParserNode()
    {
        $class = $this->getClass();
        if ($class instanceof NameExpression)
        {
            $class = $class->getName();
        }
        return new ClassConstFetch($class->convertToPhpParserNode(), $this->getName()->convertToPhpParserNode());
    }
}
