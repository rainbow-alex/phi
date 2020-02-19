<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\ValidationTraits\UnaryOpExpression;

abstract class CastExpression extends Expression
{
	use UnaryOpExpression;

	public function getRightPrecedence(): int
	{
		return self::PRECEDENCE_POW;
	}
}
