<?php

namespace Phi\Meta\Validators;

use Phi\Node;
use Phi\Token;

class IsTokenValidator implements NodeValidator
{
    private $types;

    public function __construct(...$types)
    {
        $this->types = $types;
    }

    public function getFlag(): int
    {
        return Node::VALIDATE_TOKENS;
    }

    public function validate(string $var): string
    {
        if (count($this->types) > 1)
        {
            $phpTypes = implode(",", array_map([self::class, "tokenTypeToPhp"], $this->types));
            // TODO extract string creation
            return
                "if (!in_array(" . $var . "->getType(), [" . $phpTypes . "], true))"
                . " throw new ValidationException(" . $var . '->repr() . " is expected to be one of TODO", ' . $var . ");"
            ;
        }
        else
        {
            $phpType = Token::typeToString($this->types[0]);
            return
                "if (" . $var . "->getType() !== " . $phpType . ")"
                . " throw new ValidationException(" . $var . '->repr() . " is expected to be TODO", ' . $var . ");"
            ;
        }
    }

    private static function tokenTypeToPhp(int $type): string
    {
        // TODO
        return "Token::" . \array_search($type, Token::getMap());
    }
}
