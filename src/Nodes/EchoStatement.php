<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedEchoStatement;

class EchoStatement extends GeneratedEchoStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            foreach ($this->getExpressions() as $expression)
            {
                $expression->validateContext(Expression::CTX_READ);
            }
        }
    }
}
