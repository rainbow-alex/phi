<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ArrayAccessExpression;
use Phi\Nodes\Expressions\FunctionCallExpression;
use Phi\Nodes\Expressions\MethodCallExpression;
use Phi\Nodes\Expressions\NormalVariableExpression;
use Phi\Nodes\Expressions\PropertyAccessExpression;
use Phi\Nodes\Expressions\StaticMethodCallExpression;
use Phi\Nodes\Expressions\StaticPropertyAccessExpression;
use Phi\Nodes\Generated\GeneratedBracedInterpolatedStringVariable;

class BracedInterpolatedStringVariable extends InterpolatedStringVariable
{
	use GeneratedBracedInterpolatedStringVariable;

	protected function extraValidation(int $flags): void
	{
		$variable = $this->getVariable();
		if (!(
			$variable instanceof NormalVariableExpression
			|| $variable instanceof ArrayAccessExpression
			|| $variable instanceof PropertyAccessExpression
			|| $variable instanceof FunctionCallExpression
			|| $variable instanceof MethodCallExpression
			|| $variable instanceof StaticPropertyAccessExpression
			|| $variable instanceof StaticMethodCallExpression
		))
		{
			throw ValidationException::invalidExpressionInContext($variable);
		}
	}

	public function convertToPhpParser()
	{
		return $this->getVariable()->convertToPhpParser();
	}
}
