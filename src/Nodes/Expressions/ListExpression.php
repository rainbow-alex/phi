<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedListExpression;
use PhpParser\Node\Expr\List_;

class ListExpression extends Expression
{
    use GeneratedListExpression;

    protected function extraValidation(int $flags): void
    {
        foreach ($this->getItems() as $item)
        {
            if ($byRef = $item->getByReference())
            {
                throw ValidationException::invalidSyntax($byRef);
            }

            if ($item->getValue() instanceof ArrayExpression) // and vice versa for array
            {
                throw ValidationException::invalidExpressionInContext($item->getValue());
            }
            // else: empty value is ok for write context
        }

        if (\count($this->items) === 0)
        {
            throw ValidationException::invalidExpressionInContext($this);
        }
    }

    public function convertToPhpParserNode()
    {
        $items = $this->getItems()->convertToPhpParserNode();
        if ($this->getItems()->hasTrailingSeparator())
        {
            $items[] = null;
        }
        return new List_($items);
    }
}
