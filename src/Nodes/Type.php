<?php

declare(strict_types=1);

namespace Phi\Nodes;

use Phi\Nodes\Base\CompoundNode;

abstract class Type extends CompoundNode
{
	public function unwrapNullable(): self
	{
		return $this;
	}
}
