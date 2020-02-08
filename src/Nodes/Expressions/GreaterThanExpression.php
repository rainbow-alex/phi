<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedGreaterThanExpression;
use PhpParser\Node\Expr\BinaryOp\Greater;

class GreaterThanExpression extends BinopExpression
{
    use GeneratedGreaterThanExpression;

    public function convertToPhpParserNode()
    {
        return new Greater($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
