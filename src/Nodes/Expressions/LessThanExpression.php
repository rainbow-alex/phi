<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedLessThanExpression;
use Phi\Nodes\ValidationTraits\NonAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Smaller;

class LessThanExpression extends BinopExpression
{
	use GeneratedLessThanExpression;
	use NonAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COMPARISON2;
	}

	public function convertToPhpParser()
	{
		return new Smaller($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
