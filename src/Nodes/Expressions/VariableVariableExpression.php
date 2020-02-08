<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedVariableVariableExpression;
use PhpParser\Node\Expr\Variable;

class VariableVariableExpression extends VariableExpression
{
    use GeneratedVariableVariableExpression;

    public function convertToPhpParserNode()
    {
        return new Variable($this->getName()->convertToPhpParserNode());
    }
}
