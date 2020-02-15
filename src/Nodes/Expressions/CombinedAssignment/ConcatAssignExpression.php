<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedConcatAssignExpression;
use PhpParser\Node\Expr\AssignOp\Concat;

class ConcatAssignExpression extends CombinedAssignExpression
{
	use GeneratedConcatAssignExpression;

	public function convertToPhpParser()
	{
		return new Concat($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
