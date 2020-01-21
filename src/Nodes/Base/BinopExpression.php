<?php

namespace Phi\Nodes\Base;

trait BinopExpression
{
    public function isConstant(): bool
    {
        return $this->getLeft()->isConstant() && $this->getRight()->isConstant();
    }
}
