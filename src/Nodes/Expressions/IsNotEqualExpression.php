<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsNotEqualExpression;
use PhpParser\Node\Expr\BinaryOp\NotEqual;

class IsNotEqualExpression extends BinopExpression
{
    use GeneratedIsNotEqualExpression;

    public function convertToPhpParserNode()
    {
        return new NotEqual($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
