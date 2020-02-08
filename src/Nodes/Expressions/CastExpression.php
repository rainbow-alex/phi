<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedCastExpression;
use Phi\TokenType;
use PhpParser\Node\Expr\Cast;

class CastExpression extends Expression
{
    use GeneratedCastExpression;

    public function convertToPhpParserNode()
    {
        $expr = $this->getExpression()->convertToPhpParserNode();
        switch ($this->getOperator()->getType())
        {
            case TokenType::T_ARRAY_CAST: return new Cast\Array_($expr);
            case TokenType::T_BOOL_CAST: return new Cast\Bool_($expr);
            case TokenType::T_DOUBLE_CAST: return new Cast\Double($expr);
            case TokenType::T_INT_CAST: return new Cast\Int_($expr);
            case TokenType::T_OBJECT_CAST: return new Cast\Object_($expr);
            case TokenType::T_STRING_CAST: return new Cast\String_($expr);
            case TokenType::T_UNSET_CAST: return new Cast\Unset_($expr);
            default:
                throw new \LogicException();
        }
    }
}
