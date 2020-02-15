<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPreIncrementExpression;
use PhpParser\Node\Expr\PreInc;

class PreIncrementExpression extends CrementExpression
{
	use GeneratedPreIncrementExpression;

	public function convertToPhpParser()
	{
		return new PreInc($this->getExpression()->convertToPhpParser());
	}
}
