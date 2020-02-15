<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedDivideExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use Phi\Nodes\ValidationTraits\NumericBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Div;

class DivideExpression extends BinopExpression
{
	use GeneratedDivideExpression;
	use LeftAssocBinopExpression;
	use NumericBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_MUL;
	}

	protected function extraValidation(int $flags): void
	{
		$this->validatePrecedence();
		$this->validateOperandTypes(true);
	}

	public function convertToPhpParser()
	{
		return new Div($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
