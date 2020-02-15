<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSubtractExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use Phi\Nodes\ValidationTraits\NumericBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Minus;

class SubtractExpression extends BinopExpression
{
	use GeneratedSubtractExpression;
	use LeftAssocBinopExpression;
	use NumericBinopExpression;

	public function convertToPhpParser()
	{
		return new Minus($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
		$this->validateOperandTypes();
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_ADD;
	}
}
