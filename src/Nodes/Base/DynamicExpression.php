<?php

namespace Phi\Nodes\Base;

trait DynamicExpression
{
    public function isConstant(): bool // TODO rename to isDynamic/isStatic?
    {
        return false;
    }
}
