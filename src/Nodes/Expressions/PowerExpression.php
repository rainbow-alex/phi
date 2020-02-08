<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPowerExpression;
use PhpParser\Node\Expr\BinaryOp\Pow;

class PowerExpression extends BinopExpression
{
    use GeneratedPowerExpression;

    public function convertToPhpParserNode()
    {
        return new Pow($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
