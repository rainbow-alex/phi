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
            foreach ($this->getInits() as $init)
            {
                $init->validateContext(Expression::CTX_READ);
            }
            foreach ($this->getTests() as $test)
            {
                $test->validateContext(Expression::CTX_READ);
            }
            foreach ($this->getSteps() as $step)
            {
                $step->validateContext(Expression::CTX_READ);
            }
        }
    }
}
