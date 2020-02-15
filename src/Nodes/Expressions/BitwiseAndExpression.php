<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedBitwiseAndExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\BitwiseAnd;

class BitwiseAndExpression extends BinopExpression
{
	use GeneratedBitwiseAndExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_BITWISE_AND;
	}

	public function convertToPhpParser()
	{
		return new BitwiseAnd($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
