<?php

declare(strict_types=1);

namespace Phi\Exception;

use Phi\Token;
use Phi\TokenType;

class ParseException extends SyntaxException
{
    public static function unexpected(Token $got): self
    {
        return new self("Unexpected " . TokenType::typeToString($got->getType()), $got);
    }
}
