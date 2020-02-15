<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\ParenthesizedExpression;

trait RightAssocBinopExpression
{
	abstract protected function getPrecedence(): int;
	abstract protected function getLeft(): Expression;
	abstract protected function getRight(): Expression;

	private function validatePrecedence(): void
	{
		if ($this->getLeft()->getRightPrecedence() <= $this->getLeftPrecedence())
		{
			throw ValidationException::badPrecedence($this->getLeft());
		}
		if ($this->getRight()->getLeftPrecedence() < $this->getRightPrecedence())
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
		if ($this->getLeft()->getRightPrecedence() <= $this->getLeftPrecedence())
		{
			$left = $left->wrapIn(new ParenthesizedExpression());
			$left->_autocorrect();
		}

		$right = $this->getRight();
		if ($this->getRight()->getLeftPrecedence() < $this->getRightPrecedence())
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
