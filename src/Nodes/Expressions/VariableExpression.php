<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;

// TODO VariableReference interface? so you can find all variables named x, even when it's not an expression
abstract class VariableExpression extends Expression
{
	public function isTemporary(): bool
	{
		return false;
	}
}
