<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedShiftRightExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\ShiftRight;

class ShiftRightExpression extends BinopExpression
{
	use GeneratedShiftRightExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_SHIFT;
	}

	public function convertToPhpParser()
	{
		return new ShiftRight($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
