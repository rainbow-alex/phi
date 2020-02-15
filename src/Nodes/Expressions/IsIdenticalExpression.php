<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedIsIdenticalExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Identical;

class IsIdenticalExpression extends BinopExpression
{
	use GeneratedIsIdenticalExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON1;
	}

	public function convertToPhpParser()
	{
		return new Identical($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
