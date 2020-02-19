<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Nodes;
use Phi\Nodes\Expression;

trait IsNewableHelper
{
	private static function isNewable(Expression $expression): bool
	{
		// unwrap [], ->, ::
		while (true)
		{
			if ($expression instanceof Nodes\Expressions\ArrayAccessExpression)
			{
				$expression = $expression->getExpression();

				// weird, but `new foo[expr]` is not allowed
				if ($expression instanceof Nodes\Expressions\NameExpression)
				{
					return false;
				}
			}
			else if ($expression instanceof Nodes\Expressions\PropertyAccessExpression)
			{
				$expression = $expression->getObject();
			}
			else if ($expression instanceof Nodes\Expressions\StaticPropertyAccessExpression)
			{
				$expression = $expression->getClass();
			}
			else
			{
				break;
			}
		}

		return (
			($expression instanceof Nodes\Expressions\NameExpression && $expression->getName()->isNewable())
			|| $expression instanceof Nodes\Expressions\StaticExpression
			|| $expression instanceof Nodes\Expressions\VariableExpression
		);
	}
}
