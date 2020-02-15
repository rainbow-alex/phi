<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedDivideAssignExpression;
use PhpParser\Node\Expr\AssignOp\Div;

class DivideAssignExpression extends CombinedAssignExpression
{
	use GeneratedDivideAssignExpression;

	public function convertToPhpParser()
	{
		return new Div($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
