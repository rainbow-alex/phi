<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNewExpression;
use PhpParser\Node\Expr\New_;

class NewExpression extends Expression
{
    use GeneratedNewExpression;

    protected function extraValidation(int $flags): void
    {
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
        return new New_(
            $class->convertToPhpParserNode(),
            $this->getArguments()->convertToPhpParserNode()
        );
    }
}
