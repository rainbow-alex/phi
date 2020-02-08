<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedLessThanExpression;
use PhpParser\Node\Expr\BinaryOp\Smaller;

class LessThanExpression extends BinopExpression
{
    use GeneratedLessThanExpression;

    public function convertToPhpParserNode()
    {
        return new Smaller($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
