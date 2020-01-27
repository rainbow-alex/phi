<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedFloatLiteral;

class FloatLiteral extends GeneratedFloatLiteral
{
    public function getValue(): int
    {
        return eval("return " . $this->getToken()->getSource() . ";"); // TODO
    }
}
