<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedNegationExpression;

class NegationExpression extends GeneratedNegationExpression
{
    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    use ReadOnlyExpression;
}
