<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedPropertyAccessExpression;

class PropertyAccessExpression extends GeneratedPropertyAccessExpression
{
    public function validateContext(int $flags): void
    {
        $this->getAccessee()->validateContext(self::CTX_READ);

        $name = $this->getName();
        if ($name instanceof VariableMemberName)
        {
            $name->getExpression()->validateContext(self::CTX_READ);
        }
    }
}
