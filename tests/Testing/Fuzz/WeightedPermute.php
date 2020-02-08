<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

class WeightedPermute extends FuzzRule
{
    /** @var int */
    private $max;
    /** @param array<string, int> */
    private $options;

    /**
     * @param array<string, int> $options
     */
    public function __construct(int $max, array $options)
    {
        $this->max = $max;
        $this->options = $options;
    }

    public function withMax(int $max): self
    {
        $clone = clone $this;
        $clone->max = $max;
        return $clone;
    }

    public function map(callable $fn): self
    {
        $clone = clone $this;
        $clone->options = [];
        foreach ($this->options as $option => $weight)
        {
            $clone->options[$fn((string) $option)] = $weight;
        }
        return $clone;
    }

    public function initState(&$state): void
    {
        $state = 0;
    }

    public function generateRhs(&$state)
    {
        foreach ($this->options as $rhs => $weight)
        {
            if ($state + $weight <= $this->max)
            {
                $state += $weight;
                yield $rhs;
                $state -= $weight;
            }
        }
    }
}
