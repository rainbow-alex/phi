<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedConstantStringLiteral;
use PhpParser\Node\Scalar\String_;

class ConstantStringLiteral extends GeneratedConstantStringLiteral
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
        return new String_(String_::parse($this->getSource()->getSource())); // TODO unicode test
    }
}
