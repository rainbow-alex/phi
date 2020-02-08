<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAssignmentExpression;
use PhpParser\Node\Expr\Assign;

class AssignmentExpression extends BinopExpression
{
    use GeneratedAssignmentExpression;

    public function isConstant(): bool
    {
        return false;
    }

    public function convertToPhpParserNode()
    {
        return new Assign($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }
}
