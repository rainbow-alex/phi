<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedDoWhileStatement;

class DoWhileStatement extends GeneratedDoWhileStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            $this->getTest()->validateContext(Expression::CTX_READ);
        }
    }
}
