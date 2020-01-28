<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedSuppressErrorsExpression;
use PhpParser\Node\Expr\ErrorSuppress;

class SuppressErrorsExpression extends GeneratedSuppressErrorsExpression
{
    public function validateContext(int $flags): void
    {
        $never = self::CTX_WRITE|self::CTX_ALIAS;
        if ($flags & $never)
        {
            throw ValidationException::expressionContext($flags & $never, $this);
        }

        $this->getExpression()->validateContext(self::CTX_READ);
    }

    public function convertToPhpParserNode()
    {
        return new ErrorSuppress($this->getExpression()->convertToPhpParserNode());
    }
}
