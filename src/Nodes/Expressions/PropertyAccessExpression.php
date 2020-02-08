<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedPropertyAccessExpression;
use PhpParser\Node\Expr\PropertyFetch;

class PropertyAccessExpression extends Expression
{
    use GeneratedPropertyAccessExpression;

    public function isTemporary(): bool
    {
        return false;
    }

    protected function extraValidation(int $flags): void
    {
        if (!ExpressionClassification::isObjectAccessible($this->getObject()))
        {
            throw ValidationException::invalidSyntax($this->getOperator());
        }
    }

    public function convertToPhpParserNode()
    {
        return new PropertyFetch(
            $this->getObject()->convertToPhpParserNode(),
            $this->getName()->convertToPhpParserNode()
        );
    }
}
