<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRootNode;

class RootNode extends GeneratedRootNode
{
    public function __toString(): string
    {
        return $this->getLeftWhitespace() . parent::__toString() . $this->getRightWhitespace();
    }
}
