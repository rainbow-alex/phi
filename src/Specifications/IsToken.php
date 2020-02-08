<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;
use Phi\Token;

class IsToken extends Specification
{
    /** @var int */
    private $type;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        return $node instanceof Token && $node->getType() === $this->type;
    }
}
