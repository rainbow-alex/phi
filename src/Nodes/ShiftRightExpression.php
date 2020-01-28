<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedShiftRightExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftRight;

class ShiftRightExpression extends GeneratedShiftRightExpression
{
    use PureBinopExpression;

    public function convertToPhpParserNode()
    {
        return new ShiftRight($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
