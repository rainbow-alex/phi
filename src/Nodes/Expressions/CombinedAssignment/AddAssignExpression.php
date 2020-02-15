<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedAddAssignExpression;
use PhpParser\Node\Expr\AssignOp\Plus;

class AddAssignExpression extends CombinedAssignExpression
{
	use GeneratedAddAssignExpression;

	public function convertToPhpParser()
	{
		return new Plus($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
