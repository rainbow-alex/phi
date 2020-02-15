<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedConcatExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Concat;

class ConcatExpression extends BinopExpression
{
	use GeneratedConcatExpression;
	use LeftAssocBinopExpression;

	public function convertToPhpParser()
	{
		return new Concat($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_ADD;
	}
}
