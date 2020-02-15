<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedPropertyAccessExpression;
use Phi\Nodes\ValidationTraits\IsObjectAccessibleHelper;
use PhpParser\Node\Expr\PropertyFetch;

class PropertyAccessExpression extends Expression
{
	use GeneratedPropertyAccessExpression;
	use IsObjectAccessibleHelper;

	public function isTemporary(): bool
	{
		return $this->getObject()->isConstant();
	}

	protected function extraValidation(int $flags): void
	{
		if (!self::isObjectAccessible($this->getObject()))
		{
			throw ValidationException::invalidSyntax($this->getOperator());
		}
	}

	public function convertToPhpParser()
	{
		return new PropertyFetch(
			$this->getObject()->convertToPhpParser(),
			$this->getName()->convertToPhpParser()
		);
	}
}
