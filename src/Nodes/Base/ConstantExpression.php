<?php

namespace Phi\Nodes\Base;

trait ConstantExpression
{
    public function isConstant(): bool // TODO rename to isDynamic/isStatic?
    {
        return true;
    }
}
