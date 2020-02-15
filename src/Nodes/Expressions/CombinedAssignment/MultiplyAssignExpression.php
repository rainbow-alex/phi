<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedMultiplyAssignExpression;
use PhpParser\Node\Expr\AssignOp\Mul;

class MultiplyAssignExpression extends CombinedAssignExpression
{
	use GeneratedMultiplyAssignExpression;

	public function convertToPhpParser()
	{
		return new Mul($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
