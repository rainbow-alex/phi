<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedLessThanOrEqualsExpression;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;

class LessThanOrEqualsExpression extends BinopExpression
{
    use GeneratedLessThanOrEqualsExpression;

    public function convertToPhpParserNode()
    {
        return new SmallerOrEqual($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
