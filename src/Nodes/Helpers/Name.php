<?php

declare(strict_types=1);

namespace Phi\Nodes\Helpers;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedName;
use Phi\Token;
use Phi\TokenType;
use PhpParser\Node\Name as PPName;
use PhpParser\Node\Name\FullyQualified;

class Name extends CompoundNode
{
    use GeneratedName;

    public function isAbsolute(): bool
    {
        $parts = $this->getParts()->getItems();
        return $parts && $parts[0]->getType() === TokenType::T_NS_SEPARATOR;
    }

    public function isStatic(): bool
    {
        $parts = $this->getParts()->getItems();
        return \count($parts) === 1 && $parts[0]->getType() === TokenType::T_STATIC;
    }

    public function isSpecialClass(): bool
    {
        $parts = $this->getParts()->getItems();
        return \count($parts) === 1 && self::isTokenSpecialClass($parts[0]);
    }

    public static function isTokenSpecialClass(Token $token): bool
    {
        return $token->getType() === TokenType::T_STATIC
            || $token->getSource() === "self"
            || $token->getSource() === "parent";
    }

    public function isSpecialType(): bool
    {
        $parts = $this->getParts()->getItems();
        return \count($parts) === 1 && self::isTokenSpecialType($parts[0]);
    }

    public static function isTokenSpecialType(Token $token): bool
    {
        return $token->getType() === TokenType::T_ARRAY
            || $token->getType() === TokenType::T_CALLABLE;
    }

    public function convertToPhpParserNode()
    {
        $parts = [];
        foreach ($this->getParts() as $part)
        {
            if ($part->getType() !== TokenType::T_NS_SEPARATOR)
            {
                $parts[] = $part->getSource();
            }
        }

        if ($this->isAbsolute())
        {
            return new FullyQualified($parts);
        }
        else
        {
            return new PPName($parts);
        }
    }
}
