<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsEqualExpression;
use PhpParser\Node\Expr\BinaryOp\Equal;

class IsEqualExpression extends BinopExpression
{
    use GeneratedIsEqualExpression;

    public function convertToPhpParserNode()
    {
        return new Equal($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
