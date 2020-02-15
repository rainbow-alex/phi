<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedArrayItem;

class ArrayItem extends CompoundNode
{
	use GeneratedArrayItem;

	public function convertToPhpParser()
	{
		$key = $this->getKey();
		$value = $this->getValue();
		if (!$value)
		{
			return null;
		}
		return new \PhpParser\Node\Expr\ArrayItem(
			$value->convertToPhpParser(),
			$key ? $key->getExpression()->convertToPhpParser() : null,
			$this->hasByReference(),
			[],
			$this->hasUnpack()
		);
	}
}
