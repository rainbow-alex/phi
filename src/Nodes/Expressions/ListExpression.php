<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedListExpression;
use Phi\PhpVersion;
use PhpParser\Node\Expr\List_;

class ListExpression extends Expression
{
	use GeneratedListExpression;

	protected function extraValidation(int $flags): void
	{
		$phpVersion = $this->getPhpVersion();

		foreach ($this->getItems() as $item)
		{
			if ($key = $item->getKey())
			{
				$key->getExpression()->_validate(self::CTX_READ);
			}

			if ($phpVersion < PhpVersion::PHP_7_3 && $byRef = $item->getByReference())
			{
				throw ValidationException::invalidSyntax($byRef);
			}

			if ($value = $item->getValue())
			{
				if ($value instanceof ArrayExpression) // and vice versa for array
				{
					throw ValidationException::invalidExpressionInContext($value);
				}

				if ($item->hasByReference()) // 7.3 and up, already validated
				{
					$value->_validate(self::CTX_ALIAS_WRITE);
				}
				else
				{
					$value->_validate(self::CTX_WRITE);
				}
			}
			else
			{
				if ($byRef = $item->getByReference())
				{
					throw ValidationException::invalidSyntax($byRef->getNextToken() ?? $byRef);
				}

				// empty value is otherwise ok
			}
		}

		if (\count($this->items) === 0)
		{
			throw ValidationException::invalidExpressionInContext($this);
		}
	}

	public function convertToPhpParser()
	{
		$items = $this->getItems()->convertToPhpParser();
		if ($this->getItems()->hasTrailingSeparator())
		{
			$items[] = null;
		}
		return new List_($items);
	}
}
