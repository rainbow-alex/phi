<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedCoalesceExpression;
use Phi\Nodes\ValidationTraits\RightAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Coalesce;

class CoalesceExpression extends BinopExpression
{
	use GeneratedCoalesceExpression;
	use RightAssocBinopExpression;

	protected function getPrecedence(): int
	{
		return self::PRECEDENCE_COALESCE;
	}

	public function convertToPhpParser()
	{
		return new Coalesce($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
