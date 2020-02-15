<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

abstract class ConstantStringLiteral extends StringLiteral
{
	public function isConstant(): bool
	{
		return true;
	}
}
