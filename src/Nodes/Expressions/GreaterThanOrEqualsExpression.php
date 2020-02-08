<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedGreaterThanOrEqualsExpression;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;

class GreaterThanOrEqualsExpression extends BinopExpression
{
    use GeneratedGreaterThanOrEqualsExpression;

    public function convertToPhpParserNode()
    {
        return new GreaterOrEqual($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
