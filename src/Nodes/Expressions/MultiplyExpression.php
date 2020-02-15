<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedMultiplyExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use Phi\Nodes\ValidationTraits\NumericBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Mul;

class MultiplyExpression extends BinopExpression
{
	use GeneratedMultiplyExpression;
	use LeftAssocBinopExpression;
	use NumericBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_MUL;
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
		$this->validateOperandTypes();
	}

	public function convertToPhpParser()
	{
		return new Mul($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
