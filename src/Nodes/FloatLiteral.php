<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedFloatLiteral;
use PhpParser\Node\Scalar\DNumber;

class FloatLiteral extends GeneratedFloatLiteral
{
    public function getValue(): float
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }

    public function convertToPhpParserNode()
    {
        return new DNumber($this->getValue());
    }
}
