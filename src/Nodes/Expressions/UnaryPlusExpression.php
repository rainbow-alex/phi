<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedUnaryPlusExpression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;
use PhpParser\Node\Expr\UnaryPlus;

class UnaryPlusExpression extends Expression
{
	use GeneratedUnaryPlusExpression;
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
		return new UnaryPlus($this->getExpression()->convertToPhpParser());
	}
}
