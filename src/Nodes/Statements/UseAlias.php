<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedUseAlias;
use Phi\TokenType;

class UseAlias extends CompoundNode
{
	use GeneratedUseAlias;

	protected function extraValidation(int $flags): void
	{
		$name = $this->getName();

		if (TokenType::isReservedWord($name))
		{
			throw ValidationException::invalidNameInContext($name);
		}
	}
}
