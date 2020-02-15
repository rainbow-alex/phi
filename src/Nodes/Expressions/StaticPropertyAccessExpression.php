<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticPropertyAccessExpression;
use Phi\Nodes\ValidationTraits\IsStaticAccessibleHelper;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\VarLikeIdentifier;

class StaticPropertyAccessExpression extends Expression
{
	use GeneratedStaticPropertyAccessExpression;
	use IsStaticAccessibleHelper;

	public function isTemporary(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		$class = $this->getClass();

		if (!self::isStaticAccessible($class))
		{
			throw ValidationException::invalidSyntax($this->getOperator());
		}
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		$name = $this->getName();
		if ($name instanceof NormalVariableExpression)
		{
			$ppName = new VarLikeIdentifier(substr($name->getToken()->getSource(), 1));
		}
		else
		{
			$ppName = $name->convertToPhpParser();
		}
		return new StaticPropertyFetch($class->convertToPhpParser(), $ppName);
	}
}
