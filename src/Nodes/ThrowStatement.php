<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedThrowStatement;

class ThrowStatement extends GeneratedThrowStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            $this->getExpression()->validateContext(Expression::CTX_READ);
        }
    }
}
