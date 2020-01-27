<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedReturnStatement;

class ReturnStatement extends GeneratedReturnStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            if ($this->getExpression())
            {
                $this->getExpression()->validateContext(Expression::CTX_READ);
            }
        }
    }
}
