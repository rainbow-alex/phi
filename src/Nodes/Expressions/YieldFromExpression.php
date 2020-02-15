<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedYieldFromExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use Phi\Nodes\ValidationTraits\ValidateYieldContext;

class YieldFromExpression extends Expression
{
	use GeneratedYieldFromExpression;
	use UnaryOpExpression;
	use ValidateYieldContext;

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();

		$this->validateYieldContext();
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_YIELD;
	}
}
