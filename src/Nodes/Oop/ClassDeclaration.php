<?php

declare(strict_types=1);

namespace Phi\Nodes\Oop;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedClassDeclaration;
use Phi\TokenType;

class ClassDeclaration extends OopDeclaration
{
	use GeneratedClassDeclaration;

	protected function extraValidation(int $flags): void
	{
		if (TokenType::isReservedWord($this->getName()) || TokenType::isSpecialClass($this->getName()))
		{
			throw ValidationException::invalidNameInContext($this->getName());
		}
	}
}
