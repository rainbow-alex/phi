<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedBitwiseOrExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;

class BitwiseOrExpression extends GeneratedBitwiseOrExpression
{
    use PureBinopExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseOr($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
