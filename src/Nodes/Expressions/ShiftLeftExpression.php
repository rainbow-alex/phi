<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedShiftLeftExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftLeft;

class ShiftLeftExpression extends BinopExpression
{
	use GeneratedShiftLeftExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_SHIFT;
	}

	public function convertToPhpParser()
	{
		return new ShiftLeft($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
