<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedPreIncrementExpression;
use PhpParser\Node\Expr\PreInc;

class PreIncrementExpression extends GeneratedPreIncrementExpression
{
    public function convertToPhpParserNode()
    {
        return new PreInc($this->getExpression()->convertToPhpParserNode());
    }
}
