<?php

namespace Phi\Nodes\Statements;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\AnonymousFunctionExpression;
use Phi\Nodes\Expressions\IntegerLiteral;
use Phi\Nodes\Oop\Method;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statement;

abstract class LoopFlowStatement extends Statement
{
	abstract public function getLevels(): ?IntegerLiteral;

	protected function extraValidation(int $flags): void
	{
		$maxLevels = 0;
		$foundRoot = false;
		$parent = $this->getParent();
		while ($parent)
		{
			if ($parent instanceof LoopStatement || $parent instanceof SwitchStatement)
			{
				$maxLevels++;
			}
			else if (
				$parent instanceof FunctionStatement
				|| $parent instanceof Method
				|| $parent instanceof AnonymousFunctionExpression
				|| $parent instanceof RootNode
			)
			{
				$foundRoot = true;
				break;
			}

			$parent = $parent->getParent();
		}

		$levels = $this->getLevels();

		if ($levels && $levels->getValue() <= 0)
		{
			throw ValidationException::invalidExpression($levels);
		}

		if ($foundRoot)
		{
			if ($maxLevels === 0)
			{
				throw ValidationException::invalidStatementInContext($this);
			}

			if ($levels && $levels->getValue() > $maxLevels)
			{
				throw ValidationException::invalidExpression($levels);
			}
		}
	}
}
