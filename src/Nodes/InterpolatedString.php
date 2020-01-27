<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedInterpolatedString;

class InterpolatedString extends GeneratedInterpolatedString
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        foreach ($this->getParts() as $part)
        {
            // TODO part
        }
    }
}
