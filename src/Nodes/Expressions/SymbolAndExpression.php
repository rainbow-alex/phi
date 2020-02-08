<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSymbolAndExpression;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class SymbolAndExpression extends BinopExpression
{
    use GeneratedSymbolAndExpression;

    public function convertToPhpParserNode()
    {
        return new BooleanAnd($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
