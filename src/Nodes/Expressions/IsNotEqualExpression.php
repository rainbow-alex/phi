<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsNotEqualExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\NotEqual;

class IsNotEqualExpression extends BinopExpression
{
	use GeneratedIsNotEqualExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON1;
	}

	public function convertToPhpParser()
	{
		return new NotEqual($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
