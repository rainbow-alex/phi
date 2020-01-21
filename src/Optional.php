<?php

namespace Phi;

use Phi\Specification;

class Optional
{
    /** @var Specification */
    private $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function getSpecification(): Specification
    {
        return $this->specification;
    }
}
