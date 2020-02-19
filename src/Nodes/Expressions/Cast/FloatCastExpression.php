<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedFloatCastExpression;
use PhpParser\Node\Expr\Cast\Double;

class FloatCastExpression extends CastExpression
{
	use GeneratedFloatCastExpression;

	public function convertToPhpParser()
	{
		return new Double($this->getExpression()->convertToPhpParser());
	}
}
