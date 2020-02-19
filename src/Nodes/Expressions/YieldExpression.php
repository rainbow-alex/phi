<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedYieldExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use Phi\Nodes\ValidationTraits\ValidateYieldContext;
use PhpParser\Node\Expr\Yield_;

class YieldExpression extends Expression
{
	use GeneratedYieldExpression;
	use UnaryOpExpression;
	use ValidateYieldContext;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_YIELD;
	}

	protected function extraValidation(int $flags): void
	{
		$key = $this->getKey();
		if ($key && $key->getExpression()->getPrecedence() <= $this->getPrecedence())
		{
			throw ValidationException::badPrecedence($key->getExpression());
		}

		$this->validatePrecedence();

		$this->validateYieldContext();
	}

	protected function extraAutocorrect(): void
	{
		$key = $this->getKey();
		if ($key && $key->getExpression()->getPrecedence() <= $this->getPrecedence())
		{
			$keyExpression = $key->getExpression()->wrapIn(new ParenthesizedExpression());
			$keyExpression->autocorrect();
		}

		$this->autocorrectPrecedence();
	}

	public function convertToPhpParser()
	{
		$key = $this->getKey();
		$value = $this->getExpression();
		return new Yield_(
			$value ? $value->convertToPhpParser() : null,
			$key ? $key->getExpression()->convertToPhpParser() : null
		);
	}
}
