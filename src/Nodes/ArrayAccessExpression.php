<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\ExpressionClassification;
use Phi\Nodes\Generated\GeneratedArrayAccessExpression;

class ArrayAccessExpression extends GeneratedArrayAccessExpression
{
    public function validateContext(int $flags): void
    {
        // note: this is one of the very few cases where flags are passed as they are
        // instead of explicitely specifying a new context
        /** @see Expression::CTX_READ_IMPLICIT_BY_REF */
        $this->getAccessee()->validateContext($flags);

        if (!ExpressionClassification::isArrayAccessible($this->getAccessee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        if (($flags & self::CTX_WRITE) && ExpressionClassification::isTemporary($this->getAccessee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        if ($index = $this->getIndex())
        {
            $index->validateContext(self::CTX_READ);
        }
        else
        {
            if ($flags & self::CTX_READ)
            {
                // there are some exceptions where $a[] is allowed even though it isn't usually considered read
                if (!(
                    $flags & self::CTX_READ_IMPLICIT_BY_REF // foo($a[]) is allowed
                    || $flags & self::CTX_WRITE // $a[]++ is allowed
                ))
                {
                    throw ValidationException::expressionContext(self::CTX_READ, $this);
                }
            }
        }
    }
}
