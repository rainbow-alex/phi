<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;

abstract class VariableExpression extends Expression
{
    public function isTemporary(): bool
    {
        return false;
    }
}
