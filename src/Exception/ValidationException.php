<?php

namespace Phi\Exception;

use Phi\Node;

class ValidationException extends SyntaxException
{
    public static function childRequired(Node $node, string $childName)
    {
        return new self("Child '" . $childName . "' of " . $node->repr() . " is required", $node);
    }

    public static function expressionContext(int $flags, Node $node)
    {
        return new self("Bad context", $node);
        // TODO
    }
}
