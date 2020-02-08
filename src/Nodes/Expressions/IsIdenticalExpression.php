<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsIdenticalExpression;
use PhpParser\Node\Expr\BinaryOp\Identical;

class IsIdenticalExpression extends BinopExpression
{
    use GeneratedIsIdenticalExpression;

    public function convertToPhpParserNode()
    {
        return new Identical($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
