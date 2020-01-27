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
        /** @see Expression::CTX_READ_OR_IMPLICIT_ALIAS_READ */
        $this->getAccessee()->validateContext($flags);

        if (!ExpressionClassification::isArrayAccessible($this->getAccessee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        if (($flags & self::CTX_WRITE) && ExpressionClassification::isTemporary($this->getAccessee()))
        {
            throw new ValidationException(__METHOD__, $this); // TODO
        }

        if ($this->hasIndex())
        {
            $this->getIndex()->validateContext(self::CTX_READ);
        }
        else
        {
            // if the implicit flag is set, we *ignore* READ
            // allows for e.g. foo($a[])
            if ($flags & self::CTX_READ && !($flags & self::CTX_READ_OR_IMPLICIT_ALIAS_READ))
            {
                throw ValidationException::expressionContext(self::CTX_READ, $this);
            }
        }
    }
}
