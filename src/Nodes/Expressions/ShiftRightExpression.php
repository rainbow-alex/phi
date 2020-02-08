<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedShiftRightExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftRight;

class ShiftRightExpression extends BinopExpression
{
    use GeneratedShiftRightExpression;

    public function convertToPhpParserNode()
    {
        return new ShiftRight($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
