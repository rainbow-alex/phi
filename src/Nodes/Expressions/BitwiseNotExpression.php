<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedBitwiseNotExpression;
use PhpParser\Node\Expr\BitwiseNot;

class BitwiseNotExpression extends Expression
{
	use GeneratedBitwiseNotExpression;

	public function isConstant(): bool
	{
		return $this->getExpression()->isConstant();
	}

	protected function extraValidation(int $flags): void
	{
		$expression = $this->getExpression();
		if (
			($expression instanceof ArrayExpression && $expression->isConstant())
			|| $expression instanceof ExitExpression
			|| ($expression instanceof EmptyExpression && $expression->getExpression() instanceof NumberLiteral)
			|| ($expression instanceof NotExpression && $expression->getExpression() instanceof NumberLiteral)
		)
		{
			throw ValidationException::invalidSyntax($expression);
		}
	}

	public function convertToPhpParser()
	{
		return new BitwiseNot($this->getExpression()->convertToPhpParser());
	}
}
