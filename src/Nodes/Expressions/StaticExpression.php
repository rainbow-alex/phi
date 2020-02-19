<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticExpression;
use Phi\Nodes\Oop\OopDeclaration;
use Phi\Nodes\Statements\FunctionStatement;
use PhpParser\Node\Name;

class StaticExpression extends Expression
{
	use GeneratedStaticExpression;

	public function isConstant(): bool
	{
		return true;
	}

	protected function extraValidation(int $flags): void
	{
		$parent = $this->parent;
		if ($parent && !(
			$parent instanceof ConstantAccessExpression
			|| $parent instanceof ClassNameResolutionExpression
			|| $parent instanceof StaticPropertyAccessExpression
			|| $parent instanceof StaticMethodCallExpression
			|| $parent instanceof NormalNewExpression
			|| ($parent instanceof InstanceofExpression && $parent->getClass() === $this)
		))
		{
			throw ValidationException::invalidNameInContext($this->getToken());
		}

		while ($parent = $parent->getParent())
		{
			if ($parent instanceof FunctionStatement || $parent instanceof NormalAnonymousFunctionExpression)
			{
				throw ValidationException::invalidSyntax($this);
				break;
			}
		}
	}

	public function convertToPhpParser()
	{
		return new Name([$this->getToken()->getSource()]);
	}
}
