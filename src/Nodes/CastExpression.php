<?php

namespace Phi\Nodes;

use Phi\Exception\PhiException;
use Phi\Nodes\Context\PureUnaryExpression;
use Phi\Nodes\Generated\GeneratedCastExpression;
use Phi\Token;
use Phi\TokenType;
use PhpParser\Node\Expr\Cast;

class CastExpression extends GeneratedCastExpression
{
    use PureUnaryExpression;

    public function convertToPhpParserNode()
    {
        switch ($this->getCast()->getType())
        {
            case TokenType::T_ARRAY_CAST:
                return new Cast\Array_($this->getExpression()->convertToPhpParserNode());
            case TokenType::T_BOOL_CAST:
                return new Cast\Bool_($this->getExpression()->convertToPhpParserNode());
            case TokenType::T_INT_CAST:
                return new Cast\Int_($this->getExpression()->convertToPhpParserNode());
            case TokenType::T_OBJECT_CAST:
                return new Cast\Object_($this->getExpression()->convertToPhpParserNode());
            default:
                throw new PhiException((string) $this->getCast()->getType(), $this); // TODO
        }
    }
}
