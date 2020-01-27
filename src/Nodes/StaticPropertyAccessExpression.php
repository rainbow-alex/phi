<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedStaticPropertyAccessExpression;

class StaticPropertyAccessExpression extends GeneratedStaticPropertyAccessExpression
{
    public function validateContext(int $flags): void
    {
        $this->getAccessee()->validateContext(self::CTX_READ);
    }
}
