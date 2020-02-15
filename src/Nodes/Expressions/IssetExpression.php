<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedIssetExpression;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;
use Phi\PhpVersion;
use PhpParser\Node\Expr\Isset_;

class IssetExpression extends Expression
{
	use GeneratedIssetExpression;
	use ForbidTrailingSeparator;

	protected function extraValidation(int $flags): void
	{
		foreach ($this->getExpressions() as $expression)
		{
			// TODO I think this just isn't a "temporary" check at all
			if (
				(
					$expression->isTemporary()
					&& !$expression instanceof ArrayAccessExpression
					&& !$expression instanceof PropertyAccessExpression
				)
				|| $expression instanceof FunctionCallExpression
				|| $expression instanceof MethodCallExpression
				|| $expression instanceof StaticMethodCallExpression
			)
			{
				throw ValidationException::invalidExpressionInContext($expression);
			}
		}

		if ($this->getPhpVersion() < PhpVersion::PHP_7_3)
		{
			self::forbidTrailingSeparator($this->getExpressions());
		}
	}

	public function convertToPhpParser()
	{
		$expressions = [];
		foreach ($this->getExpressions() as $phiExpr)
		{
			$expressions[] = $phiExpr->convertToPhpParser();
		}
		return new Isset_($expressions);
	}
}
