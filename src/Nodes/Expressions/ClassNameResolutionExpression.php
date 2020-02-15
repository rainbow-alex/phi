<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedClassNameResolutionExpression;
use PhpParser\Node\Expr\ClassConstFetch;

class ClassNameResolutionExpression extends Expression
{
	use GeneratedClassNameResolutionExpression;

	public function isConstant(): bool
	{
		return $this->getClass()->isConstant();
	}

	protected function extraValidation(int $flags): void
	{
		$class = $this->getClass();
		if (!(
			$class instanceof NameExpression
			|| $class instanceof StaticExpression
			|| $class instanceof StringLiteral
		))
		{
			throw ValidationException::invalidExpressionInContext($class);
		}
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		return new ClassConstFetch($class->convertToPhpParser(), $this->getKeyword()->convertToPhpParser());
	}
}
