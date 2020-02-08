<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

class Combine extends FuzzRule
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
        $state = [];
    }

    public function generateRhs(&$state)
    {
        foreach ($this->options as $option)
        {
            if (!isset($state[$option]))
            {
                $state[$option] = true;
                yield $option;
                unset($state[$option]);
            }
        }
    }
}
