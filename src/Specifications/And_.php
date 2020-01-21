<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;

class And_ extends Specification
{
    /** @var Specification[] */
    private $specifications;

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }

    public function isSatisfiedBy(Node $node): bool
    {
        foreach ($this->specifications as $specification)
        {
            if (!$specification->isSatisfiedBy($node))
            {
                return false;
            }
        }

        return true;
    }

    public function validate(Node $node): void
    {
        foreach ($this->specifications as $specification)
        {
            $specification->validate($node);
        }
    }

    public function autocorrect(?Node $node): ?Node
    {
        foreach ($this->specifications as $specification)
        {
            $node = $specification->autocorrect($node);
        }

        return $node;
    }
}
