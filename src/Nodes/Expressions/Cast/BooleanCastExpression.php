<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedBooleanCastExpression;
use PhpParser\Node\Expr\Cast\Bool_;

class BooleanCastExpression extends CastExpression
{
	use GeneratedBooleanCastExpression;

	public function convertToPhpParser()
	{
		return new Bool_($this->getExpression()->convertToPhpParser());
	}
}
