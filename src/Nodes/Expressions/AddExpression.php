<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAddExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use Phi\Nodes\ValidationTraits\NumericBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Plus;

class AddExpression extends BinopExpression
{
	use GeneratedAddExpression;
	use LeftAssocBinopExpression;
	use NumericBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_ADD;
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
		$this->validateOperandTypes();
	}

	public function convertToPhpParser()
	{
		return new Plus($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
