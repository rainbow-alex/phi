<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ArrayExpression;
use Phi\Nodes\Expressions\NumberLiteral;

trait NumericBinopExpression
{
	abstract protected function getLeft(): Expression;
	abstract protected function getRight(): Expression;

	private function validateOperandTypes(bool $leftOnly = false): void
	{
		$left = $this->getLeft();
		$right = $this->getRight();

		if ($left instanceof ArrayExpression && $right instanceof NumberLiteral && $left->isConstant())
		{
			throw ValidationException::invalidExpressionInContext($left);
		}

		if (!$leftOnly && $left instanceof NumberLiteral && $right instanceof ArrayExpression && $right->isConstant())
		{
			throw ValidationException::invalidExpressionInContext($right);
		}
	}
}
