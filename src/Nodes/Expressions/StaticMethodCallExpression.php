<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticMethodCallExpression;
use Phi\Nodes\Helpers\NormalMemberName;
use PhpParser\Node\Expr\StaticCall;

class StaticMethodCallExpression extends Expression
{
    use GeneratedStaticMethodCallExpression;

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
        if ($name instanceof NormalMemberName)
        {
            $name = $name->getToken();
        }
        return new StaticCall(
            $class->convertToPhpParserNode(),
            $name->convertToPhpParserNode(),
            $this->getArguments()->convertToPhpParserNode()
        );
    }
}
