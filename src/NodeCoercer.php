<?php

declare(strict_types=1);

namespace Phi;

use Phi\Exception\CoercionException;
use Phi\Exception\TodoException;
use Phi\Nodes\Blocks\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\Expressions\IntegerLiteral;
use Phi\Nodes\Statement;
use Phi\Nodes\Statements\ExpressionStatement;

class NodeCoercer
{
	/**
	 * @param Node|string $value
	 */
	public static function coerce($value, string $target, int $phpVersion): Node
	{
		if (\is_string($value))
		{
			throw new TodoException;
		}

		$result = self::tryCoerce($value, $target, $phpVersion);

		if (!$result)
		{
			if ($value instanceof $target)
			{
				throw new CoercionException("Failed to convert " . $value->repr() . " to " . $target, $value);
			}
			else
			{
				throw new CoercionException("Failed to convert " . \var_export($value, true) . " to " . $target, null);
			}
		}

		assert($result instanceof $target);

		return $result;
	}

	private static function tryCoerce(Node $value, string $target, int $phpVersion): ?Node
	{
		if ($value instanceof $target)
		{
			return $value;
		}

		if ($target === RegularBlock::class)
		{
			/** @var Statement|null $asStatement */
			$asStatement = self::tryCoerce($value, Statement::class, $phpVersion);
			if ($asStatement)
			{
				return new RegularBlock($asStatement);
			}
		}

		if ($target === Statement::class)
		{
			/** @var Expression|null $asExpression */
			$asExpression = self::tryCoerce($value, Expression::class, $phpVersion);
			if ($asExpression)
			{
				return new ExpressionStatement($asExpression);
			}
		}

		if ($target === Expression::class)
		{
			if ($value instanceof ExpressionStatement)
			{
				return $value->getExpression();
			}

			if ($value instanceof Token)
			{
				if ($value->getType() === TokenType::T_LNUMBER)
				{
					return new IntegerLiteral($value);
				}
			}
		}

		if ($value instanceof $target)
		{
			return $value;
		}

		return null;
	}
}
