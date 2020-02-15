<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Nodes\Expression;
use Phi\PhpVersion;
use PhpParser\Node\Expr\Array_;

abstract class ArrayExpression extends Expression
{
	/**
	 * @return SeparatedNodesList|ArrayItem[]
	 * @phpstan-return SeparatedNodesList<\Phi\Nodes\Expressions\ArrayItem>
	 */
	abstract public function getItems(): SeparatedNodesList;

	public function isConstant(): bool
	{
		foreach ($this->getItems() as $item)
		{
			if ($key = $item->getKey())
			{
				if (!$key->getExpression()->isConstant())
				{
					return false;
				}
			}

			if ($item->hasByReference())
			{
				return false;
			}

			$value = $item->getValue();
			if (!$value || !$value->isConstant())
			{
				return false;
			}
		}

		return true;
	}

	protected function extraValidation(int $flags): void
	{
		$phpVersion = $this->getPhpVersion();

		if ($flags & self::CTX_READ)
		{
			// (only) in read we keep track of whether the array is constant
			// if it is completely, we will run an additional check on the key types
			$constant = true;

			foreach ($this->getItems() as $item)
			{
				if ($key = $item->getKey())
				{
					$constant = $constant && $key->getExpression()->isConstant();

					$key->getExpression()->_validate(self::CTX_READ);
				}

				if ($unpack = $item->getUnpack())
				{
					if ($phpVersion < PhpVersion::PHP_7_4)
					{
						throw ValidationException::invalidSyntax($unpack);
					}

					$value = $item->getValue();
					if ($value instanceof NumberLiteral)
					{
						throw ValidationException::invalidExpressionInContext($value);
					}

					if ($byRef = $item->getByReference())
					{
						throw ValidationException::invalidSyntax($byRef);
					}
				}

				if ($value = $item->getValue())
				{
					if ($item->hasByReference())
					{
						$constant = false;

						$value->_validate(self::CTX_READ | self::CTX_ALIAS_WRITE);
					}
					else
					{
						$constant = $constant && $value->isConstant();

						$value->_validate(self::CTX_READ);
					}
				}
				else
				{
					throw ValidationException::invalidExpressionInContext($this);
				}
			}

			if ($constant)
			{
				foreach ($this->getItems() as $item)
				{
					if ($key = $item->getKey())
					{
						if ($key->getExpression() instanceof ArrayExpression && $key->getExpression()->isConstant())
						{
							throw ValidationException::invalidExpressionInContext($key->getExpression());
						}
					}
				}
			}
		}
		else if ($flags & self::CTX_WRITE)
		{
			$empty = true;

			foreach ($this->getItems() as $item)
			{
				if ($key = $item->getKey())
				{
					$key->getExpression()->_validate(self::CTX_READ);
					// note with write the key check isn't done
				}

				if ($unpack = $item->getUnpack())
				{
					throw ValidationException::invalidSyntax($unpack);
				}

				if ($phpVersion < PhpVersion::PHP_7_3 && $item->hasByReference())
				{
					throw ValidationException::invalidExpressionInContext($this);
				}

				if ($value = $item->getValue())
				{
					$empty = false;

					if ($value instanceof ListExpression) // and vice versa for list
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
				// else: empty value is ok for write context
			}

			if ($empty)
			{
				throw ValidationException::invalidExpressionInContext($this);
			}
		}
	}

	public function convertToPhpParser()
	{
		$items = [];
		foreach ($this->getItems() as $phiItem)
		{
			$items[] = $phiItem->convertToPhpParser();
		}
		return new Array_($items);
	}
}
