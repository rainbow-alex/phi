<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseOrExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;

class BitwiseOrExpression extends BinopExpression
{
    use GeneratedBitwiseOrExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseOr($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
