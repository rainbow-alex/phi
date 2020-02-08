<?php

declare(strict_types=1);

namespace Phi\Exception;

use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Nodes\Helpers\Name;
use Phi\Nodes\Statement;
use Phi\Token;
use Phi\TokenType;

class ValidationException extends SyntaxException
{
    public static function missingChild(Node $node, string $childName): self
    {
        return new self("Child '" . $childName . "' of " . $node->repr() . " is required", $node);
    }

    /**
     * @param int[] $expected expected token types
     */
    public static function invalidSyntax(Node $node, ?array $expected = null): self
    {
        if (!$expected)
        {
            return new self("Invalid syntax", $node);
        }
        else
        {
            return new self(
                "Token is expected to be one of " . \implode(", ", \array_map([TokenType::class, "typeToString"], $expected)),
                $node
            );
        }
    }

    public static function missingWhitespace(Node $node): self
    {
        return new self("Missing whitespace", $node);
    }

    public static function invalidExpression(Expression $node, ?Node $pointAt = null): self
    {
        return new self("Invalid expression", $pointAt ?? $node);
    }

    public static function invalidStatementInContext(Statement $node): self
    {
        return new self("Statement is not valid in this context", $node);
    }

    public static function invalidExpressionInContext(Expression $node, ?Node $pointAt = null): self
    {
        return new self("Expression is not valid in this context", $pointAt ?? $node);
    }

    public static function badPrecedence(Expression $node): self
    {
        return new self("Expression is of the wrong precedence for this place", $node);
    }

    /** @param Name|Token $node */
    public static function invalidNameInContext(Node $node): self
    {
        return new self("Can't use this name in this context", $node);
    }
}
