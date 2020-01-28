<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedBitwiseAndExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;

class BitwiseAndExpression extends GeneratedBitwiseAndExpression
{
    use PureBinopExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseAnd($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
