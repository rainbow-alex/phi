<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

trait SymmetricPrecedence
{
	abstract protected function getPrecedence(): int;

	public function getLeftPrecedence(): int
	{
		return $this->getPrecedence();
	}

	public function getRightPrecedence(): int
	{
		return $this->getPrecedence();
	}
}
