<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedMethodCallExpression;
use Phi\Nodes\ValidationTraits\IsObjectAccessibleHelper;
use PhpParser\Node\Expr\MethodCall;

class MethodCallExpression extends Expression
{
	use GeneratedMethodCallExpression;
	use IsObjectAccessibleHelper;

	public function isTemporary(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		if (!self::isObjectAccessible($this->getObject()))
		{
			throw ValidationException::invalidSyntax($this->getOperator());
		}
	}

	public function convertToPhpParser()
	{
		return new MethodCall(
			$this->getObject()->convertToPhpParser(),
			$this->getName()->convertToPhpParser(),
			$this->getArguments()->convertToPhpParser()
		);
	}
}
