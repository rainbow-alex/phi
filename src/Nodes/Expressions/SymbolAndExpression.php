<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSymbolAndExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

class SymbolAndExpression extends BinopExpression
{
	use GeneratedSymbolAndExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_SYMBOL_AND;
	}

	public function convertToPhpParser()
	{
		return new BooleanAnd($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
