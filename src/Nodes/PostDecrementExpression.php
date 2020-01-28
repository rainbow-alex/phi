<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedPostDecrementExpression;
use PhpParser\Node\Expr\PostDec;

class PostDecrementExpression extends GeneratedPostDecrementExpression
{
    public function convertToPhpParserNode()
    {
        return new PostDec($this->getExpression()->convertToPhpParserNode());
    }
}
