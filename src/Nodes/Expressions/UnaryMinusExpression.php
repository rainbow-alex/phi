<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedUnaryMinusExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use PhpParser\Node\Expr\UnaryMinus;

class UnaryMinusExpression extends Expression
{
	use GeneratedUnaryMinusExpression;
	use UnaryOpExpression;

	public function getRightPrecedence(): int
	{
		return self::PRECEDENCE_POW;
	}

	public function isConstant(): bool
	{
		return $this->getExpression()->isConstant();
	}

	protected function extraValidation(int $flags): void
	{
		$expression = $this->getExpression();
		if ($expression instanceof ArrayExpression && $expression->isConstant())
		{
			throw ValidationException::invalidSyntax($expression);
		}

		$this->validatePrecedence();
	}

	public function convertToPhpParser()
	{
		return new UnaryMinus($this->getExpression()->convertToPhpParser());
	}
}
