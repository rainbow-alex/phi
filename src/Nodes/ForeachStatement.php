<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedForeachStatement;

class ForeachStatement extends GeneratedForeachStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            $this->getIterable()->validateContext(Expression::CTX_READ);

            if ($key = $this->getKey())
            {
                $key->getValue()->validateContext(Expression::CTX_WRITE);

                if ($key->getValue() instanceof ListExpression || $key->getValue() instanceof ShortArrayExpression) // TODO extract/improve
                {
                    throw new ValidationException(__METHOD__, $this->getKey());
                }
            }

            if ($this->hasByReference())
            {
                $this->getValue()->validateContext(Expression::CTX_ALIAS_WRITE);
            }
            else
            {
                $this->getValue()->validateContext(Expression::CTX_WRITE);
            }
        }
    }
}
