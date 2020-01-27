<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedSwitchStatement;

class SwitchStatement extends GeneratedSwitchStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            $this->getValue()->validateContext(Expression::CTX_READ);
        }
    }
}
