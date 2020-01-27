<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRegularVariableExpression;

class RegularVariableExpression extends GeneratedRegularVariableExpression
{
    public function validateContext(int $flags): void
    {
        // valid in all contexts
    }
}
