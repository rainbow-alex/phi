<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

class Permute extends FuzzRule
{
    /** @param string[] */
    private $options;

    /**
     * @param string[] $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public function initState(&$state): void
    {
    }

    public function generateRhs(&$state)
    {
        yield from $this->options;
    }
}
