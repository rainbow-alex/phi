<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedObjectCastExpression;
use PhpParser\Node\Expr\Cast\Object_;

class ObjectCastExpression extends CastExpression
{
	use GeneratedObjectCastExpression;

	public function convertToPhpParser()
	{
		return new Object_($this->getExpression()->convertToPhpParser());
	}
}
