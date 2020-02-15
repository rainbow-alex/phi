<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ParenthesizedExpression;

trait UnaryOpExpression
{
	abstract protected function getExpression(): ?Expression;

	private function validatePrecedence(): void
	{
		$expression = $this->getExpression();
		if ($expression && $expression->getLeftPrecedence() < $this->getRightPrecedence())
		{
			throw ValidationException::badPrecedence($expression);
		}
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
	}

	private function autocorrectPrecedence(): void
	{
		$expression = $this->getExpression();
		if ($expression && $expression->getLeftPrecedence() < $this->getRightPrecedence())
		{
			$expression = $expression->wrapIn(new ParenthesizedExpression());
			$expression->_autocorrect();
		}
	}

	protected function extraAutocorrect(): void
	{
		$this->autocorrectPrecedence();
	}
}
