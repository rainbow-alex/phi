<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRegularVariableExpression;

class RegularVariableExpression extends GeneratedRegularVariableExpression
{
    public function validateContext(int $flags): void
    {
        // valid in all contexts
    }

    public function convertToPhpParserNode()
    {
        return new \PhpParser\Node\Expr\Variable(substr($this->getVariable()->getSource(), 1));
    }
}
