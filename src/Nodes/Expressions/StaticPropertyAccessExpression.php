<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticPropertyAccessExpression;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\VarLikeIdentifier;

class StaticPropertyAccessExpression extends Expression
{
    use GeneratedStaticPropertyAccessExpression;

    public function isTemporary(): bool
    {
        return false;
    }

    protected function extraValidation(int $flags): void
    {
        if (!ExpressionClassification::isStaticAccessible($this->getClass()))
        {
            throw ValidationException::invalidSyntax($this->getOperator());
        }
    }

    public function convertToPhpParserNode()
    {
        $class = $this->getClass();
        if ($class instanceof NameExpression)
        {
            $class = $class->getName();
        }
        $name = $this->getName();
        if ($name instanceof NormalVariableExpression)
        {
            $ppName = new VarLikeIdentifier(substr($name->getToken()->getSource(), 1));
        }
        else
        {
            $ppName = $name->convertToPhpParserNode();
        }
        return new StaticPropertyFetch($class->convertToPhpParserNode(), $ppName);
    }
}
