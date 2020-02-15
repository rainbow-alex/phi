<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedShiftRightAssignExpression;
use PhpParser\Node\Expr\AssignOp\ShiftRight;

class ShiftRightAssignExpression extends CombinedAssignExpression
{
	use GeneratedShiftRightAssignExpression;

	public function convertToPhpParser()
	{
		return new ShiftRight($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
