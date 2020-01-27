<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedForStatement;

class ForStatement extends GeneratedForStatement
{
    protected function _validate(int $flags): void
    {
        parent::_validate($flags);

        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
            if ($init = $this->getInit())
            {
                $init->validateContext(Expression::CTX_READ);
            }
            if ($test = $this->getTest())
            {
                $test->validateContext(Expression::CTX_READ);
            }
            if ($step = $this->getStep())
            {
                $step->validateContext(Expression::CTX_READ);
            }
        }
    }
}
