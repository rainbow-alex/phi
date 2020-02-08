<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedArgument;
use PhpParser\Node\Arg;

class Argument extends CompoundNode
{
    use GeneratedArgument;

    public function convertToPhpParserNode()
    {
        return new Arg($this->getExpression()->convertToPhpParserNode(), false, $this->hasUnpack());
    }
}
