<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;

class Any extends Specification
{
    public function isSatisfiedBy(Node $node): bool
    {
        return true;
    }
}
