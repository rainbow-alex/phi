<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedStaticMethodCallExpression;
use Phi\Nodes\Helpers\NormalMemberName;
use Phi\Nodes\ValidationTraits\ForbidTrailingSeparator;
use Phi\Nodes\ValidationTraits\IsStaticAccessibleHelper;
use PhpParser\Node\Expr\StaticCall;

class StaticMethodCallExpression extends Expression
{
	use GeneratedStaticMethodCallExpression;
	use IsStaticAccessibleHelper;
	use ForbidTrailingSeparator;

	public function isTemporary(): bool
	{
		return false;
	}

	protected function extraValidation(int $flags): void
	{
		if (!self::isStaticAccessible($this->getClass()))
		{
			throw ValidationException::invalidSyntax($this->getOperator());
		}

		self::forbidTrailingSeparator($this->getArguments());
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		$name = $this->getName();
		if ($name instanceof NormalMemberName)
		{
			$name = $name->getToken();
		}
		return new StaticCall(
			$class->convertToPhpParser(),
			$name->convertToPhpParser(),
			$this->getArguments()->convertToPhpParser()
		);
	}
}
