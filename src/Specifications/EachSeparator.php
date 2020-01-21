<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Specification;

class EachSeparator extends Specification
{
    /** @var Specification */
    private $separatorSpecification;

    public function __construct(Specification $separatorSpecification)
    {
        $this->separatorSpecification = $separatorSpecification;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        if ($node instanceof SeparatedNodesList)
        {
            foreach ($node->getSeparators() as $separator)
            {
                if (!$this->separatorSpecification->isSatisfiedBy($separator))
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }
}
