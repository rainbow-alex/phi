<?php

declare(strict_types=1);

namespace Phi;

/**
 * @template N of Node
 */
interface WrapperNode
{
	/**
	 * @phpstan-param N $node
	 */
	public function wrapNode(Node $node): void;
}
