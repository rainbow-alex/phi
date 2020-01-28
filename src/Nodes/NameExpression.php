<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedNameExpression;
use PhpParser\Node\Expr\ConstFetch;

class NameExpression extends GeneratedNameExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }
    }

    public function convertToPhpParserNode()
    {
        return new ConstFetch($this->getName()->convertToPhpParserNode());
    }
}
