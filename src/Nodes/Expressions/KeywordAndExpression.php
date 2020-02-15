<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedKeywordAndExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;

class KeywordAndExpression extends BinopExpression
{
	use GeneratedKeywordAndExpression;
	use LeftAssocBinopExpression;

	public function convertToPhpParser()
	{
		return new LogicalAnd($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_KEYWORD_AND;
	}
}
