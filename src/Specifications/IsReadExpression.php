<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Nodes\Expression;
use Phi\Specification;

class IsReadExpression extends Specification
{
    public function isSatisfiedBy(Node $node): bool
    {
        return $node instanceof Expression && $node->isRead();
    }
}
