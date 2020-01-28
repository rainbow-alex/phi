<?php

namespace Phi\Nodes;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedIncludeLikeExpression;
use Phi\TokenType;
use PhpParser\Node\Expr\Include_;

class IncludeLikeExpression extends GeneratedIncludeLikeExpression
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
        $type = [
            TokenType::T_INCLUDE => Include_::TYPE_INCLUDE,
            TokenType::T_INCLUDE_ONCE => Include_::TYPE_INCLUDE_ONCE,
            TokenType::T_REQUIRE => Include_::TYPE_REQUIRE,
            TokenType::T_REQUIRE_ONCE => Include_::TYPE_REQUIRE_ONCE,
        ][$this->getKeyword()->getType()];
        return new Include_($this->getExpression()->convertToPhpParserNode(), $type);
    }
}
