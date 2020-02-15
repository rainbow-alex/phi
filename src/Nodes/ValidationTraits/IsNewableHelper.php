<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Nodes;
use Phi\Nodes\Expression;

trait IsNewableHelper
{
	private static function isNewable(Expression $expression, bool $nested = false): bool
	{
		// TODO use a loop
		if ($expression instanceof Nodes\Expressions\ArrayAccessExpression)
		{
			return self::isNewable($expression->getExpression(), true);
		}
		else if ($expression instanceof Nodes\Expressions\PropertyAccessExpression)
		{
			return self::isNewable($expression->getObject(), true);
		}
		else
		{
			return (
				$expression instanceof Nodes\Expressions\StaticPropertyAccessExpression
				|| $expression instanceof Nodes\Expressions\StaticExpression
				|| (!$nested && $expression instanceof Nodes\Expressions\NameExpression && $expression->getName()->isNewable())
				|| $expression instanceof Nodes\Expressions\VariableExpression
			);
		}
	}
}
