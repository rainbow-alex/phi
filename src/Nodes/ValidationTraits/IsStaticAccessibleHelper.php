<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Nodes;
use Phi\Nodes\Expression;

trait IsStaticAccessibleHelper
{
	public static function isStaticAccessible(Expression $expression): bool
	{
		if ($expression instanceof Nodes\Expressions\NameExpression)
		{
			return $expression->getName()->isStaticAccessible();
		}

		return !(
			$expression instanceof Nodes\Expressions\AnonymousFunctionExpression
			|| $expression instanceof Nodes\Expressions\ClassNameResolutionExpression
			|| $expression instanceof Nodes\Expressions\ConstantAccessExpression
			|| $expression instanceof Nodes\Expressions\EmptyExpression
			|| $expression instanceof Nodes\Expressions\EvalExpression
			|| $expression instanceof Nodes\Expressions\ExececutionExpression
			|| $expression instanceof Nodes\Expressions\ExitExpression
			|| $expression instanceof Nodes\Expressions\IssetExpression
			|| $expression instanceof Nodes\Expressions\MagicConstant
			|| $expression instanceof Nodes\Expressions\NewExpression
			|| $expression instanceof Nodes\Expressions\NumberLiteral
			|| ($expression instanceof Nodes\Expressions\ArrayExpression && $expression->isConstant())
		);
	}
}
