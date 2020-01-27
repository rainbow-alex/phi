<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedArgument;

class Argument extends GeneratedArgument
{
    public function validateContext(): void
    {
        if (!$this->hasUnpack())
        {
            $this->getExpression()->validateContext(Expression::CTX_READ|Expression::CTX_READ_OR_IMPLICIT_ALIAS_READ);
        }
        else
        {
            $this->getExpression()->validateContext(Expression::CTX_READ);
        }
    }
}
