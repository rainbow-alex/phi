<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedNormalVariableExpression;
use PhpParser\Node\Expr\Variable;

class NormalVariableExpression extends VariableExpression
{
    use GeneratedNormalVariableExpression;

    public function convertToPhpParserNode()
    {
        return new Variable(substr($this->getToken()->getSource(), 1));
    }
}
