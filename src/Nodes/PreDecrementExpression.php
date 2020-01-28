<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedPreDecrementExpression;
use PhpParser\Node\Expr\PreDec;

class PreDecrementExpression extends GeneratedPreDecrementExpression
{
    public function convertToPhpParserNode()
    {
        return new PreDec($this->getExpression()->convertToPhpParserNode());
    }
}
