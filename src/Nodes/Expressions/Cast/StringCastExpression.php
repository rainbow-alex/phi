<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedStringCastExpression;
use PhpParser\Node\Expr\Cast\String_;

class StringCastExpression extends CastExpression
{
	use GeneratedStringCastExpression;

	public function convertToPhpParser()
	{
		return new String_($this->getExpression()->convertToPhpParser());
	}
}
