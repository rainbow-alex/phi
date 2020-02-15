<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedPowerAssignExpression;
use PhpParser\Node\Expr\AssignOp\Pow;

class PowerAssignExpression extends CombinedAssignExpression
{
	use GeneratedPowerAssignExpression;

	public function convertToPhpParser()
	{
		return new Pow($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
