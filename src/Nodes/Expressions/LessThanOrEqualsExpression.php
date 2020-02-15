<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedLessThanOrEqualsExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\SmallerOrEqual;

class LessThanOrEqualsExpression extends BinopExpression
{
	use GeneratedLessThanOrEqualsExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON2;
	}

	public function convertToPhpParser()
	{
		return new SmallerOrEqual($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
