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
            if ($expression = $this->getExpression())
            {
                $expression->validateContext(Expression::CTX_READ);
            }
        }
    }
}
