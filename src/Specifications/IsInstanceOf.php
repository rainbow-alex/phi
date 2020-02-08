<?php

declare(strict_types=1);

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;

class IsInstanceOf extends Specification
{
    /** @var string */
    private $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        return $node instanceof $this->class;
    }
}
