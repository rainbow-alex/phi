<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedTernaryExpression;
use PhpParser\Node\Expr\Ternary;

class TernaryExpression extends Expression
{
	use GeneratedTernaryExpression;

	public function isConstant(): bool
	{
		$then = $this->getIf();
		return $this->getCondition()->isConstant()
			&& (!$then || $then->isConstant())
			&& $this->getElse()->isConstant();
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_TERNARY;
	}

	protected function extraValidation(int $flags): void
	{
		$condition = $this->getCondition();
		if ($condition->getPrecedence() < $this->getPrecedence())
		{
			throw ValidationException::badPrecedence($condition);
		}

		$else = $this->getElse();
		if ($else->getPrecedence() <= $this->getPrecedence())
		{
			throw ValidationException::badPrecedence($else);
		}
	}

	protected function extraAutocorrect(): void
	{
		$condition = $this->getCondition();
		if ($condition->getPrecedence() < $this->getPrecedence())
		{
			$condition = $condition->wrapIn(new ParenthesizedExpression());
			$condition->_autocorrect();
		}

		$else = $this->getElse();
		if ($else->getPrecedence() <= $this->getPrecedence())
		{
			$else = $else->wrapIn(new ParenthesizedExpression());
			$else->_autocorrect();
		}
	}

	public function convertToPhpParser()
	{
		return new Ternary(
			$this->getCondition()->convertToPhpParser(),
			($if = $this->getIf()) ? $if->convertToPhpParser() : null,
			$this->getElse()->convertToPhpParser()
		);
	}
}
