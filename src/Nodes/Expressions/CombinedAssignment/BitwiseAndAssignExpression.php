<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedBitwiseAndAssignExpression;
use PhpParser\Node\Expr\AssignOp\BitwiseAnd;

class BitwiseAndAssignExpression extends CombinedAssignExpression
{
	use GeneratedBitwiseAndAssignExpression;

	public function convertToPhpParser()
	{
		return new BitwiseAnd($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
