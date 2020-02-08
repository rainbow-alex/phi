<?php

declare(strict_types=1);

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
}
