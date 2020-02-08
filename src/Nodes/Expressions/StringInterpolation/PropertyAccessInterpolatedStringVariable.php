<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Generated\GeneratedPropertyAccessInterpolatedStringVariable;
use PhpParser\Node\Expr\PropertyFetch;

class PropertyAccessInterpolatedStringVariable extends InterpolatedStringVariable
{
    use GeneratedPropertyAccessInterpolatedStringVariable;

    public function convertToPhpParserNode()
    {
        return new PropertyFetch(
            $this->getVariable()->convertToPhpParserNode(),
            $this->getName()->getSource()
        );
    }
}
