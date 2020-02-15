<?php

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ParenthesizedExpression;

trait NonAssocBinopExpression
{
	private function validatePrecedence(): void
	{
		if ($this->getLeft()->getPrecedence() <= $this->getPrecedence())
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
		if ($this->getLeft()->getPrecedence() <= $this->getPrecedence())
		{
			$left = $this->getLeft();
			$wrapped = new ParenthesizedExpression($left);
			$wrapped->_autocorrect();
			$this->setLeft($wrapped);
		}

		if ($this->getRight()->getPrecedence() <= $this->getPrecedence())
		{
			$right = $this->getRight();
			$wrapped = new ParenthesizedExpression($right);
			$wrapped->_autocorrect();
			$this->setRight($wrapped);
		}
	}

	protected function extraAutocorrect(): void
	{
		$this->autocorrectPrecedence();
	}
}
