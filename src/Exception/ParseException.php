<?php

namespace Phi\Exception;

use Phi\Token;

class ParseException extends SyntaxException
{
    public static function unexpected(Token $got): self
    {
        return new self('Unexpected ' . Token::typeToString($got->getType()), $got);
    }
}
