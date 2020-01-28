<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedShiftLeftExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftLeft;

class ShiftLeftExpression extends GeneratedShiftLeftExpression
{
    use PureBinopExpression;

    public function convertToPhpParserNode()
    {
        return new ShiftLeft($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
