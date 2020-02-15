<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedGreaterThanExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Greater;

class GreaterThanExpression extends BinopExpression
{
	use GeneratedGreaterThanExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON2;
	}

	public function convertToPhpParser()
	{
		return new Greater($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
