<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedIntegerLiteral;

class IntegerLiteral extends GeneratedIntegerLiteral
{
    public function getValue(): int
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }
}
