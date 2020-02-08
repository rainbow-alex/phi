<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedFloatLiteral;
use PhpParser\Node\Scalar\DNumber;

class FloatLiteral extends NumberLiteral
{
    use GeneratedFloatLiteral;

    public function getValue(): float
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }

    public function convertToPhpParserNode()
    {
        return new DNumber($this->getValue());
    }
}
