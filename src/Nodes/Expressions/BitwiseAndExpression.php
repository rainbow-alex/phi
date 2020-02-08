<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseAndExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;

class BitwiseAndExpression extends BinopExpression
{
    use GeneratedBitwiseAndExpression;

    public function convertToPhpParserNode()
    {
        return new BitwiseAnd($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
