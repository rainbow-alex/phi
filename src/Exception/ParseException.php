<?php

namespace Phi\Exception;

use Phi\Token;

class ParseException extends PhiException
{
    public static function unexpected(Token $got): self
    {
        return new self('Unexpected ' . Token::typeToString($got->getType()), $got);
    }

    /**
     * @param int|string $expected
     */
    public static function expected($expected, Token $got): self
    {
        if (!\is_string($expected) || strlen($expected) === 1)
        {
            $expected = Token::typeToString($expected);
        }

        return new self('Expected ' . $expected . ', got ' . Token::typeToString($got->getType()), $got);
    }
}
