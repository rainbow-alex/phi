<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedBitwiseOrAssignExpression;
use PhpParser\Node\Expr\AssignOp\BitwiseOr;

class BitwiseOrAssignExpression extends CombinedAssignExpression
{
	use GeneratedBitwiseOrAssignExpression;

	public function convertToPhpParser()
	{
		return new BitwiseOr($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
