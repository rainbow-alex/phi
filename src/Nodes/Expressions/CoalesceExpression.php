<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedCoalesceExpression;
use PhpParser\Node\Expr\BinaryOp\Coalesce;

class CoalesceExpression extends BinopExpression
{
    use GeneratedCoalesceExpression;

    public function convertToPhpParserNode()
    {
        return new Coalesce($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
