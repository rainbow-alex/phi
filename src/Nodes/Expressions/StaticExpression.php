<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticExpression;

class StaticExpression extends Expression
{
	use GeneratedStaticExpression;

	public function isConstant(): bool
	{
		return true;
	}

	protected function extraValidation(int $flags): void
	{
		if (!(
			$this->parent instanceof ConstantAccessExpression
			|| $this->parent instanceof ClassNameResolutionExpression
			|| $this->parent instanceof StaticPropertyAccessExpression
			|| $this->parent instanceof StaticMethodCallExpression
			|| $this->parent instanceof NewExpression
		))
		{
			throw ValidationException::invalidNameInContext($this->getToken());
		}
	}
}
