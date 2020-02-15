<?php

namespace Phi\Specifications;

use Phi\Node;
use Phi\Specification;

class HasParent extends Specification
{
	/** @var Specification */
	private $parentSpecification;

	public function __construct(Specification $parentSpecification)
	{
		$this->parentSpecification = $parentSpecification;
	}

	public function isSatisfiedBy(Node $node): bool
	{
		while ($node = $node->getParent())
		{
			if ($this->parentSpecification->isSatisfiedBy($node))
			{
				return true;
			}
		}

		return false;
	}
}
