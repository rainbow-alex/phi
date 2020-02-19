<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedArrayCastExpression;
use PhpParser\Node\Expr\Cast\Array_;

class ArrayCastExpression extends CastExpression
{
	use GeneratedArrayCastExpression;

	public function convertToPhpParser()
	{
		return new Array_($this->getExpression()->convertToPhpParser());
	}
}
