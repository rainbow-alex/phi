<?php

namespace Phi\Exception;

use Phi\Node;

class ValidationException extends SyntaxException
{
    public static function childRequired(Node $node, string $childName): self
    {
        $node->debugDump();
        return new self("Child '" . $childName . "' of " . $node->repr() . " is required", $node);
    }

    public static function expressionContext(int $flags, Node $node): self
    {
        return new self("Bad context", $node);
        // TODO
    }
}
