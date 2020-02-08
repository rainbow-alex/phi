<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAliasingExpression;
use PhpParser\Node\Expr\AssignRef;

class AliasingExpression extends BinopExpression
{
    use GeneratedAliasingExpression;

    public function isConstant(): bool
    {
        return false;
    }

    public function convertToPhpParserNode()
    {
        return new AssignRef($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
