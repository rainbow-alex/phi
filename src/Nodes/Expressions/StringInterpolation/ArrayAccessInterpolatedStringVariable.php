<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedArrayAccessInterpolatedStringVariable;
use PhpParser\Node\Expr\ArrayDimFetch;

class ArrayAccessInterpolatedStringVariable extends InterpolatedStringVariable
{
    use GeneratedArrayAccessInterpolatedStringVariable;

    public function convertToPhpParserNode()
    {
        return new ArrayDimFetch(
            $this->getVariable()->convertToPhpParserNode(),
            $this->getIndex()->convertToPhpParserNode()
        );
    }
}
