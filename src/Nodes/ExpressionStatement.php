<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedExpressionStatement;

class ExpressionStatement extends GeneratedExpressionStatement
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
