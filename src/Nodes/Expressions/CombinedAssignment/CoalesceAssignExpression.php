<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedCoalesceAssignExpression;
use PhpParser\Node\Expr\AssignOp\Coalesce;

class CoalesceAssignExpression extends CombinedAssignExpression
{
	use GeneratedCoalesceAssignExpression;

	public function convertToPhpParser()
	{
		return new Coalesce($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
