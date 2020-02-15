<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ParenthesizedExpression;

trait LeftAssocBinopExpression
{
	abstract protected function getPrecedence(): int;
	abstract protected function getLeft(): Expression;
	abstract protected function getRight(): Expression;

	private function validatePrecedence(): void
	{
		if ($this->getLeft()->getPrecedence() < $this->getPrecedence())
		{
			throw ValidationException::badPrecedence($this->getLeft());
		}
		if ($this->getRight()->getPrecedence() <= $this->getPrecedence())
		{
			throw ValidationException::badPrecedence($this->getRight());
		}
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
	}

	private function autocorrectPrecedence(): void
	{
		$left = $this->getLeft();
		if ($left->getPrecedence() < $this->getPrecedence())
		{
			$left = $left->wrapIn(new ParenthesizedExpression());
			$left->_autocorrect();
		}

		$right = $this->getRight();
		if ($right->getPrecedence() <= $this->getPrecedence())
		{
			$right = $right->wrapIn(new ParenthesizedExpression());
			$right->_autocorrect();
		}
	}

	protected function extraAutocorrect(): void
	{
		$this->autocorrectPrecedence();
	}
}
