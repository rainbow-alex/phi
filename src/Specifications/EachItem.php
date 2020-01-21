<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Specification;

class EachItem extends Specification
{
    /** @var Specification */
    private $itemSpecification;

    public function __construct(Specification $itemSpecification)
    {
        $this->itemSpecification = $itemSpecification;
    }

    public function getItemSpecification(): Specification
    {
        return $this->itemSpecification;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        if ($node instanceof NodesList || $node instanceof SeparatedNodesList)
        {
            foreach ($node->getItems() as $item)
            {
                if (!$this->itemSpecification->isSatisfiedBy($item))
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
