<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseXorExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseXor;

class BitwiseXorExpression extends BinopExpression
{
	use GeneratedBitwiseXorExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_BITWISE_XOR;
	}

	public function convertToPhpParser()
	{
		return new BitwiseXor($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
