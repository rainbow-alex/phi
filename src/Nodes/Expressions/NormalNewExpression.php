<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedNormalNewExpression;
use Phi\Nodes\ValidationTraits\IsNewableHelper;
use PhpParser\Node\Expr\New_;

class NormalNewExpression extends NewExpression
{
	use GeneratedNormalNewExpression;
	use IsNewableHelper;

	protected function extraValidation(int $flags): void
	{
		if (!self::isNewable($this->getClass()))
		{
			throw ValidationException::invalidExpressionInContext($this->getClass());
		}
	}

	public function convertToPhpParser()
	{
		$class = $this->getClass();
		if ($class instanceof NameExpression)
		{
			$class = $class->getName();
		}
		return new New_(
			$class->convertToPhpParser(),
			$this->getArguments()->convertToPhpParser()
		);
	}
}
