<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Nodes;
use Phi\Nodes\Expression;

trait IsObjectAccessibleHelper
{
	public static function isObjectAccessible(Expression $expression): bool
	{
		return !(
			$expression instanceof Nodes\Expressions\NormalAnonymousFunctionExpression
			|| $expression instanceof Nodes\Expressions\ClassNameResolutionExpression
			|| $expression instanceof Nodes\Expressions\ConstantAccessExpression
			|| $expression instanceof Nodes\Expressions\EmptyExpression
			|| $expression instanceof Nodes\Expressions\EvalExpression
			|| $expression instanceof Nodes\Expressions\ExececutionExpression
			|| $expression instanceof Nodes\Expressions\ExitExpression
			|| $expression instanceof Nodes\Expressions\IssetExpression
			|| $expression instanceof Nodes\Expressions\CloneExpression
			|| $expression instanceof Nodes\Expressions\MagicConstant
			|| $expression instanceof Nodes\Expressions\NameExpression
			|| $expression instanceof Nodes\Expressions\NewExpression
			|| $expression instanceof Nodes\Expressions\NumberLiteral
		);
	}
}
