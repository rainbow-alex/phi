<?php

declare(strict_types=1);

namespace Phi;

abstract class Specification
{
    abstract public function isSatisfiedBy(Node $node): bool;
}
