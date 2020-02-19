<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedConstStatement;
use Phi\Nodes\Statement;
use Phi\TokenType;
use PhpParser\Node\Stmt\Const_;

class ConstStatement extends Statement
{
	use GeneratedConstStatement;

	protected function extraValidation(int $flags): void
	{
		if (TokenType::isSpecialConst($this->getName()))
		{
			throw ValidationException::invalidSyntax($this->getName());
		}

		if (!$this->getValue()->isConstant())
		{
			throw ValidationException::invalidExpressionInContext($this->getValue());
		}
	}

	public function convertToPhpParser()
	{
		// TODO multi
		return new Const_(
			[
				new \PhpParser\Node\Const_($this->getName()->getSource(), $this->getValue()->convertToPhpParser()),
			]
		);
	}
}
