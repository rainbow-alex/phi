<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseXorExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseXor;

class BitwiseXorExpression extends BinopExpression
{
    use GeneratedBitwiseXorExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseXor($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
