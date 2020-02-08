<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedNormalInterpolatedStringVariable;
use PhpParser\Node\Expr\Variable;

class NormalInterpolatedStringVariable extends InterpolatedStringVariable
{
    use GeneratedNormalInterpolatedStringVariable;

    public function convertToPhpParserNode()
    {
        return new Variable(\substr($this->getVariable()->getSource(), 1));
    }
}
