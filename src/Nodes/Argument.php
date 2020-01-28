<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedArgument;
use PhpParser\Node\Arg;

class Argument extends GeneratedArgument
{
    public function validateContext(): void
    {
        if (!$this->hasUnpack())
        {
            $this->getExpression()->validateContext(Expression::CTX_READ|Expression::CTX_READ_IMPLICIT_BY_REF);
        }
        else
        {
            $this->getExpression()->validateContext(Expression::CTX_READ);
        }
    }

    public function convertToPhpParserNode()
    {
        return new Arg($this->getExpression()->convertToPhpParserNode(), false, $this->hasUnpack());
    }
}
