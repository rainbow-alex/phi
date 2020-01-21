<?php

namespace Phi;

use Phi\Exception\ConversionException;
use Phi\Nodes\Block;
use Phi\Nodes\Expression;
use Phi\Nodes\ExpressionStatement;
use Phi\Nodes\NumberLiteral;
use Phi\Nodes\Statement;

class NodeConverter
{
    /**
     * @param Node|string $value
     */
    public static function convert($value, string $target, ?int $phpVersion = null): Node // TODO make version required?
    {
        $result = self::tryConvert($value, $target, $phpVersion ?? PhpVersion::DEFAULT());

        if (!$result)
        {
            throw new ConversionException('Failed to convert to ' . $target, $value);
        }

        assert($result instanceof $target);

        return $result;
    }

    /**
     * @param Node|string $value
     */
    private static function tryConvert($value, string $target, int $phpVersion): ?Node
    {
        if (is_string($value))
        {
            $parser = new Parser($phpVersion);
            $value = $parser->parseFragment($value);
            // TODO handle parse exception
        }

        if ($value instanceof $target)
        {
            return $value;
        }

        if ($target === Block::class)
        {
            if ($asStatement = self::tryConvert($value, Statement::class, $phpVersion))
            {
                return new Block($asStatement);
            }
        }

        if ($target === Statement::class)
        {
            $asExpression = self::tryConvert($value, Expression::class, $phpVersion);
            if ($asExpression && $asExpression->isRead())
            {
                return new ExpressionStatement($value);
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
                if ($value->getType() === \T_LNUMBER)
                {
                    return new NumberLiteral($value);
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
