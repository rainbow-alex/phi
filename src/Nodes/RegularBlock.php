<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedRegularBlock;

class RegularBlock extends GeneratedRegularBlock
{
    public function convertToPhpParserNode()
    {
        return $this->getStatements()->convertToPhpParserNode();
    }
}
