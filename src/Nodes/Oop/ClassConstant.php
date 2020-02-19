<?php

declare(strict_types=1);

namespace Phi\Nodes\Oop;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedClassConstant;
use Phi\TokenType;
use PhpParser\Node\Const_;
use PhpParser\Node\Stmt\ClassConst;

class ClassConstant extends OopMember
{
	use GeneratedClassConstant;

	protected function extraValidation(int $flags): void
	{
		if ($this->getName()->getSource() === 'class')
		{
			throw ValidationException::invalidSyntax($this->getName(), [TokenType::T_STRING]);
		}
	}

	public function convertToPhpParser()
	{
		// TODO multi
		return new ClassConst(
			[
				new Const_($this->getName()->getSource(), $this->getValue()->convertToPhpParser()),
			]
		);
	}
}
