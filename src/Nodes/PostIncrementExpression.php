<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedPostIncrementExpression;
use PhpParser\Node\Expr\PostInc;

class PostIncrementExpression extends GeneratedPostIncrementExpression
{
    public function convertToPhpParserNode()
    {
        return new PostInc($this->getExpression()->convertToPhpParserNode());
    }
}
