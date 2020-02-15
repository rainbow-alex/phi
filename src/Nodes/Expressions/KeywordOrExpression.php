<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedKeywordOrExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;

class KeywordOrExpression extends BinopExpression
{
	use GeneratedKeywordOrExpression;
	use LeftAssocBinopExpression;

	public function convertToPhpParser()
	{
		return new LogicalOr($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_KEYWORD_OR;
	}
}
