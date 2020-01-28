<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedRegularAssignmentExpression;
use PhpParser\Node\Expr\Assign;

class RegularAssignmentExpression extends GeneratedRegularAssignmentExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getLvalue()->validateContext(self::CTX_WRITE);
        $this->getValue()->validateContext(self::CTX_READ);
    }

    public function convertToPhpParserNode()
    {
        return new Assign($this->getLvalue()->convertToPhpParserNode(), $this->getValue()->convertToPhpParserNode());
    }
}
