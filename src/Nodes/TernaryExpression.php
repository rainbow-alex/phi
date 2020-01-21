<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedTernaryExpression;

class TernaryExpression extends GeneratedTernaryExpression
{
    public function isConstant(): bool
    {
        $then = $this->getThen();
        return $this->getTest()->isConstant()
            && (!$then || $then->isConstant())
            && $this->getElse()->isConstant();
    }

    use ReadOnlyExpression;
}
