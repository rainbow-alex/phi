<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAssignExpression;
use PhpParser\Node\Expr\Assign;

class AssignExpression extends BinopExpression
{
	use GeneratedAssignExpression;

	public function isConstant(): bool
	{
		return false;
	}

	public function convertToPhpParser()
	{
		return new Assign($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
