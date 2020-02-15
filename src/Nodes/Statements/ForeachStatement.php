<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ListExpression;
use Phi\Nodes\Expressions\ShortArrayExpression;
use Phi\Nodes\Generated\GeneratedForeachStatement;

class ForeachStatement extends LoopStatement
{
	use GeneratedForeachStatement;

	protected function extraValidation(int $flags): void
	{
		if ($key = $this->getKey())
		{
			if ($key->getExpression() instanceof ListExpression || $key->getExpression() instanceof ShortArrayExpression)
			{
				throw ValidationException::invalidExpressionInContext($key->getExpression());
			}
		}

		// TODO do we test by ref + destruct?
		$value = $this->getValue();
		if ($this->hasByReference() && $value->isTemporary())
		{
			throw ValidationException::invalidExpressionInContext($value);
		}
	}
}
