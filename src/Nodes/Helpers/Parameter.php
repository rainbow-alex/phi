<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedParameter;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;

class Parameter extends CompoundNode
{
    use GeneratedParameter;

    public function convertToPhpParserNode()
    {
        $type = $this->getType();
        $default = $this->getDefault();
        return new Param(
            new Variable(substr($this->getVariable()->getSource(), 1)),
            $default ? $default->convertToPhpParserNode() : null,
            $type ? $type->convertToPhpParserNode() : null,
            $this->hasByReference(),
            $this->hasUnpack()
        );
    }
}
