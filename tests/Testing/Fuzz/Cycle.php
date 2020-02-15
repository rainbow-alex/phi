<?php

declare(strict_types=1);

namespace Phi\Tests\Testing\Fuzz;

class Cycle extends FuzzRule
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
		$state = 0;
	}

	public function generateRhs(&$state)
	{
		yield $this->options[$state++ % count($this->options)];
		$state--;
	}
}
