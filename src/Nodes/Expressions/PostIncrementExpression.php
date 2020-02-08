<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPostIncrementExpression;
use PhpParser\Node\Expr\PostInc;

class PostIncrementExpression extends CrementExpression
{
    use GeneratedPostIncrementExpression;

    public function convertToPhpParserNode()
    {
        return new PostInc($this->getExpression()->convertToPhpParserNode());
    }
}
