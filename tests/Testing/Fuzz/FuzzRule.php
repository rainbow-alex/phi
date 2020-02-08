<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

abstract class FuzzRule
{
    abstract public function initState(&$state): void;

    /** @return iterable<string> */
    abstract public function generateRhs(&$state);
}
