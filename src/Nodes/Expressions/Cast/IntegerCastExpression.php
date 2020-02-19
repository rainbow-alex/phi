<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedIntegerCastExpression;
use PhpParser\Node\Expr\Cast\Int_;

class IntegerCastExpression extends CastExpression
{
	use GeneratedIntegerCastExpression;

	public function convertToPhpParser()
	{
		return new Int_($this->getExpression()->convertToPhpParser());
	}
}
