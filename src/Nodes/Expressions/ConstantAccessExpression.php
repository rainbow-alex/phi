<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedConstantAccessExpression;
use Phi\Nodes\ValidationTraits\IsStaticAccessibleHelper;
use PhpParser\Node\Expr\ClassConstFetch;

class ConstantAccessExpression extends Expression
{
	use GeneratedConstantAccessExpression;
	use IsStaticAccessibleHelper;

	public function isConstant(): bool
	{
		return $this->getClass()->isConstant() && !($this->getClass() instanceof ArrayAccessExpression); // TODO ?
	}

	protected function extraValidation(int $flags): void
	{
		$class = $this->getClass();

		if (!self::isStaticAccessible($class))
		{
			throw ValidationException::invalidSyntax($class);
		}

		// TODO (3)::foo, (3)::foo(), and probably a lot of others are also invalid, but not covered by tests
		if (
			($class instanceof ParenthesizedExpression && $class->isConstant())
			|| $class instanceof NumberLiteral
		)
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
