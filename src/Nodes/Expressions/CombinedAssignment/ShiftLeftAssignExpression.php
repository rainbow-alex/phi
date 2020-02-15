<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedShiftLeftAssignExpression;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;

class ShiftLeftAssignExpression extends CombinedAssignExpression
{
	use GeneratedShiftLeftAssignExpression;

	public function convertToPhpParser()
	{
		return new ShiftLeft($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
