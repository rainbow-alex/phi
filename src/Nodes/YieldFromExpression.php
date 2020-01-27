<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedYieldFromExpression;

class YieldFromExpression extends GeneratedYieldFromExpression
{
    public function validateContext(int $flags): void
    {
        $this->getExpression()->validateContext(self::CTX_READ);
    }
}
