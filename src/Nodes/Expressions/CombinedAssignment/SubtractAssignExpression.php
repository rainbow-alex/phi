<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedSubtractAssignExpression;
use PhpParser\Node\Expr\AssignOp\Minus;

class SubtractAssignExpression extends CombinedAssignExpression
{
	use GeneratedSubtractAssignExpression;

	public function convertToPhpParser()
	{
		return new Minus($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
