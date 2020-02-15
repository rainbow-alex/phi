<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseOrExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;

class BitwiseOrExpression extends BinopExpression
{
	use GeneratedBitwiseOrExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_BITWISE_OR;
	}

	public function convertToPhpParser()
	{
		return new BitwiseOr($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
