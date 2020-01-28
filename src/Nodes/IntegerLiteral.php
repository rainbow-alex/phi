<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedIntegerLiteral;
use PhpParser\Node\Scalar\LNumber;

class IntegerLiteral extends GeneratedIntegerLiteral
{
    public function getValue(): int
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }

    public function convertToPhpParserNode()
    {
        return new LNumber($this->getValue());
    }
}
