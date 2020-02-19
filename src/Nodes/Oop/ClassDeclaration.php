<?php

declare(strict_types=1);

namespace Phi\Nodes\Oop;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedClassDeclaration;
use Phi\TokenType;
use PhpParser\Node\Stmt\Class_;

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

	public function convertToPhpParser()
	{
		$extends = $this->getExtends();
		$implements = $this->getImplements();
		return new Class_($this->getName()->getSource(), [
			'extends' => $extends ? $extends->convertToPhpParser() : null,
			'implements' => $implements ? $implements->convertToPhpParser() : null,
			'stmts' => $this->members->convertToPhpParser(),
		]);
	}
}
