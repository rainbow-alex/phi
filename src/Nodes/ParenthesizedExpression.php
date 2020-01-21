<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedParenthesizedExpression;

class ParenthesizedExpression extends GeneratedParenthesizedExpression
{
    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    use ReadOnlyExpression;
}
