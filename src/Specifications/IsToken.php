<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;
use Phi\Token;

class IsToken extends Specification
{
    /** @var array<int|string> */
    private $types;

    /**
     * @param array<int|string> $types
     */
    public function __construct(...$types)
    {
        assert(!is_array($types[0]));
        $this->types = $types;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        return $node instanceof Token && in_array($node->getType(), $this->types, true);
    }

    protected function validationErrorMessage(Node $node): string
    {
        if (count($this->types) > 1)
        {
            $types = \implode(', ', \array_map([Token::class, 'typeToString'], $this->types));
            return $node->repr() . ' is expected to be one of ' . $types;
        }
        else
        {
            $type = Token::typeToString($this->types[0]);
            return $node->repr() . ' is expected to be ' . $type;
        }
    }

    public function autocorrect(?Node $node): ?Node
    {
        if (!$node && count($this->types))
        {
            $type = $this->types[0];
            if (\is_string($type))
            {
                return new Token($type, $type);
            }
            else if (isset(self::AUTOCORRECT[$type]))
            {
                return new Token($type, self::AUTOCORRECT[$type]);
            }
        }

        return $node;
    }

    private const AUTOCORRECT = [
        \T_FUNCTION => 'function',
    ];
}
