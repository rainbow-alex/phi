<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedBitwiseXorAssignExpression;
use PhpParser\Node\Expr\AssignOp\BitwiseXor;

class BitwiseXorAssignExpression extends CombinedAssignExpression
{
	use GeneratedBitwiseXorAssignExpression;

	public function convertToPhpParser()
	{
		return new BitwiseXor($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
