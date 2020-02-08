<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedShiftLeftExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftLeft;

class ShiftLeftExpression extends BinopExpression
{
    use GeneratedShiftLeftExpression;

    public function convertToPhpParserNode()
    {
        return new ShiftLeft($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
