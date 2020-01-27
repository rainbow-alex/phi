<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedVariableVariableExpression;

class VariableVariableExpression extends GeneratedVariableVariableExpression
{
    public function validateContext(int $flags): void
    {
        // valid in all contexts

        $this->getName()->validateContext(self::CTX_READ);
    }
}
