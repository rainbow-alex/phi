<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;

abstract class BinopExpression extends Expression
{
	abstract public function getLeft(): Expression;
	abstract public function getRight(): Expression;

	public function isConstant(): bool
	{
		return $this->getLeft()->isConstant() && $this->getRight()->isConstant();
	}
}
