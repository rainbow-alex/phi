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

		if (
			$expression instanceof Nodes\Expressions\NormalAnonymousFunctionExpression
			|| $expression instanceof Nodes\Expressions\NotExpression
			|| $expression instanceof Nodes\Expressions\CastExpression
			|| $expression instanceof Nodes\Expressions\UnaryPlusExpression
			|| $expression instanceof Nodes\Expressions\UnaryMinusExpression
			|| $expression instanceof Nodes\Expressions\SuppressErrorsExpression
			|| $expression instanceof Nodes\Expressions\CloneExpression
			|| $expression instanceof Nodes\Expressions\IncludeLikeExpression
			|| $expression instanceof Nodes\Expressions\BitwiseNotExpression
			|| $expression instanceof Nodes\Expressions\IssetExpression
			|| $expression instanceof Nodes\Expressions\NewExpression
			|| $expression instanceof Nodes\Expressions\EmptyExpression
			|| $expression instanceof Nodes\Expressions\EvalExpression
			|| $expression instanceof Nodes\Expressions\CrementExpression
			|| $expression instanceof Nodes\Expressions\MagicConstant
			|| $expression instanceof Nodes\Expressions\ConstantAccessExpression
			|| $expression instanceof Nodes\Expressions\ExececutionExpression
		)
		{
			 return false;
		}

		while ($expression instanceof Nodes\Expressions\ParenthesizedExpression)
		{
			$expression = $expression->getExpression();
		}

		return !(
			false
			|| $expression instanceof Nodes\Expressions\PrintExpression
			|| $expression instanceof Nodes\Expressions\ClassNameResolutionExpression
			|| $expression instanceof Nodes\Expressions\ExitExpression
			|| $expression instanceof Nodes\Expressions\NumberLiteral
			|| ($expression instanceof Nodes\Expressions\ArrayExpression && \count($expression->getItems()) === 0)
			// || ($expression instanceof Nodes\Expressions\ArrayExpression && $expression->isConstant())
		);
	}
}
