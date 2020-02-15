<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedPreDecrementExpression;
use PhpParser\Node\Expr\PreDec;

class PreDecrementExpression extends CrementExpression
{
	use GeneratedPreDecrementExpression;

	public function convertToPhpParser()
	{
		return new PreDec($this->getExpression()->convertToPhpParser());
	}
}
