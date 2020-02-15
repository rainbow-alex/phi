<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedAliasExpression;
use PhpParser\Node\Expr\AssignRef;

class AliasExpression extends BinopExpression
{
	use GeneratedAliasExpression;

	public function isConstant(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		$left = $this->getLeft();

		if ($left->isTemporary())
		{
			throw ValidationException::invalidExpressionInContext($left);
		}

		$right = $this->getRight();
		if ($right->isTemporary())
		{
			throw ValidationException::invalidExpressionInContext($right);
		}
	}

	public function convertToPhpParser()
	{
		return new AssignRef($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
