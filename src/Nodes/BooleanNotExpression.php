<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedBooleanNotExpression;

class BooleanNotExpression extends GeneratedBooleanNotExpression
{
    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    use ReadOnlyExpression;
}
