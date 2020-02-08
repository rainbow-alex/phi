<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart;
use Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringVariable;

abstract class InterpolatedStringLiteral extends StringLiteral
{
    /**
     * @return NodesList|InterpolatedStringPart[]
     * @phpstan-return NodesList<InterpolatedStringPart>
     */
    abstract public function getParts(): NodesList;

    public function isConstant(): bool
    {
        foreach ($this->getParts() as $part)
        {
            if ($part instanceof InterpolatedStringVariable)
            {
                return false;
            }
        }

        return true;
    }
}
