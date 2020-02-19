<?php

declare(strict_types=1);

namespace Phi\Nodes\ValidationTraits;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expressions\NormalAnonymousFunctionExpression;
use Phi\Nodes\Oop\Method;
use Phi\Nodes\RootNode;
use Phi\Nodes\Statements\FunctionStatement;

trait ValidateYieldContext
{
	private function validateYieldContext(): void
	{
		for ($parent = $this->getParent(); $parent; $parent = $parent->getParent())
		{
			if (
				$parent instanceof FunctionStatement
				|| $parent instanceof Method
				|| $parent instanceof NormalAnonymousFunctionExpression
			)
			{
				break;
			}
			else if ($parent instanceof RootNode)
			{
				throw ValidationException::invalidExpressionInContext($this);
			}
		}
	}
}
