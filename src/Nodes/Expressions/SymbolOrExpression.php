<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSymbolOrExpression;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class SymbolOrExpression extends BinopExpression
{
    use GeneratedSymbolOrExpression;

    public function convertToPhpParserNode()
    {
        return new BooleanOr($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
