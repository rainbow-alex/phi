<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\Cast;

use Phi\Nodes\Expressions\CastExpression;
use Phi\Nodes\Generated\GeneratedUnsetCastExpression;
use PhpParser\Node\Expr\Cast\Unset_;

class UnsetCastExpression extends CastExpression
{
	use GeneratedUnsetCastExpression;

	public function convertToPhpParser()
	{
		return new Unset_($this->getExpression()->convertToPhpParser());
	}
}
