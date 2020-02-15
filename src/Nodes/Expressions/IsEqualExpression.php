<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsEqualExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Equal;

class IsEqualExpression extends BinopExpression
{
	use GeneratedIsEqualExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON1;
	}

	public function convertToPhpParser()
	{
		return new Equal($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
