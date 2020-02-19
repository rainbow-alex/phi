<?php

declare(strict_types=1);

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\ListExpression;
use Phi\Nodes\Expressions\ShortArrayExpression;
use Phi\Nodes\Generated\GeneratedForeachStatement;
use PhpParser\Node\Stmt\Foreach_;

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

		$value = $this->getValue();
		if ($this->hasByReference() && $value->isTemporary())
		{
			throw ValidationException::invalidExpressionInContext($value);
		}
	}

	public function convertToPhpParser()
	{
		$key = $this->getKey();
		return new Foreach_(
			$this->getIterable()->convertToPhpParser(),
			$this->getValue()->convertToPhpParser(),
			[
				'keyVar' => $key ? $key->getExpression()->convertToPhpParser() : null,
				'byRef' => $this->hasByReference(),
				'stmts' => $this->getBlock()->convertToPhpParser(),
			]
		);
	}
}
