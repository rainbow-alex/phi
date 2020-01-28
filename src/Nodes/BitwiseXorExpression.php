<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedBitwiseXorExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseXor;

class BitwiseXorExpression extends GeneratedBitwiseXorExpression
{
    use PureBinopExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseXor($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
