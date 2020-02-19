<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedConstantAccessExpression;
use Phi\Nodes\ValidationTraits\IsConstantClassNameHelper;
use Phi\Nodes\ValidationTraits\IsStaticAccessibleHelper;
use PhpParser\Node\Expr\ClassConstFetch;

class ConstantAccessExpression extends Expression
{
	use GeneratedConstantAccessExpression;
	use IsStaticAccessibleHelper;
	use IsConstantClassNameHelper;

	public function isConstant(): bool
	{
		return self::isConstantClassName($this->getClass());
	}

	protected function extraValidation(int $flags): void
	{
		$class = $this->getClass();

		if (!self::isStaticAccessible($class))
		{
			throw ValidationException::invalidSyntax($class);
		}
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		return new ClassConstFetch($class->convertToPhpParser(), $this->getName()->convertToPhpParser());
	}
}
