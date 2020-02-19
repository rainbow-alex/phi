<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Expressions\ArrayExpression;
use Phi\Nodes\Expressions\NameExpression;
use Phi\Nodes\Expressions\ParenthesizedExpression;
use Phi\Nodes\Expressions\StaticExpression;

trait IsConstantClassNameHelper
{
	/**
	 * Can the expression be used as the name of a class in a constant expression.
	 * i.e. is `const foo = EXPR::class;` valid.
	 */
	private static function isConstantClassName(Expression $expression): bool
	{
		if ($expression instanceof ParenthesizedExpression)
		{
			$expression = $expression->getExpression();

			if ($expression instanceof NameExpression)
			{
				return false;
			}
		}

		if (
			$expression instanceof StaticExpression
			|| $expression instanceof ArrayExpression
			|| $expression instanceof ArrayAccessExpression
		)
		{
			return false;
		}

		return $expression->isConstant();
	}
}
