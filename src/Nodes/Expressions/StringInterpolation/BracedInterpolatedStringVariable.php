<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedBracedInterpolatedStringVariable;
use PhpParser\Node\Expr\Variable;

class BracedInterpolatedStringVariable extends InterpolatedStringVariable
{
    use GeneratedBracedInterpolatedStringVariable;

    public function convertToPhpParserNode()
    {
        return new Variable($this->getName()->getSource());
    }
}
