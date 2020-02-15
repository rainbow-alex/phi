<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;

abstract class CombinedAssignExpression extends BinopExpression
{
	public function isConstant(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		if ($this->getLeft()->isTemporary())
		{
			throw ValidationException::invalidExpressionInContext($this->getLeft());
		}
	}
}
