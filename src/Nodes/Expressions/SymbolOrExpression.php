<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSymbolOrExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;

class SymbolOrExpression extends BinopExpression
{
	use GeneratedSymbolOrExpression;
	use LeftAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_SYMBOL_OR;
	}

	public function convertToPhpParser()
	{
		return new BooleanOr($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
