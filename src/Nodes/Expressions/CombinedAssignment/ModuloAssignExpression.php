<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\CombinedAssignment;

use Phi\Nodes\Expressions\CombinedAssignExpression;
use Phi\Nodes\Generated\GeneratedModuloAssignExpression;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModuloAssignExpression extends CombinedAssignExpression
{
	use GeneratedModuloAssignExpression;

	public function convertToPhpParser()
	{
		return new Mod($this->getLeft()->convertToPhpParser(), $this->getRight()->convertToPhpParser());
	}
}
