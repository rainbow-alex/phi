<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsNotIdenticalExpression;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;

class IsNotIdenticalExpression extends BinopExpression
{
    use GeneratedIsNotIdenticalExpression;

    public function convertToPhpParserNode()
    {
        return new NotIdentical($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
