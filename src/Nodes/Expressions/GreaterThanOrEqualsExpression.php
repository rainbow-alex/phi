<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedGreaterThanOrEqualsExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;

class GreaterThanOrEqualsExpression extends BinopExpression
{
	use GeneratedGreaterThanOrEqualsExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON2;
	}

	public function convertToPhpParser()
	{
		return new GreaterOrEqual($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
