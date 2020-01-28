<?php

namespace Phi;

use Phi\Exception\ConversionException;
use Phi\Exception\SyntaxException;
use Phi\Exception\ValidationException;
use Phi\Nodes\Statement;
use Phi\Nodes\RegularBlock;
use Phi\Nodes\Expression;
use Phi\Nodes\ExpressionStatement;
use Phi\Nodes\IntegerLiteral;

class NodeConverter
{
    /**
     * @param Node|string $value
     */
    public static function convert($value, string $target, ?int $phpVersion): Node
    {
        $result = self::tryConvert($value, $target, $phpVersion ?? PhpVersion::DEFAULT());

        if (!$result)
        {
            if ($value instanceof Node)
            {
                throw new ConversionException("Failed to convert " . $value->repr() . " to " . $target, $value);
            }
            else
            {
                throw new ConversionException("Failed to convert " . \var_export($value, true) . " to " . $target, null);
            }
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
            try
            {
                $value = $parser->parseFragment($value);
            }
            catch (SyntaxException $e)
            {
                return null;
            }
        }

        if ($value instanceof $target)
        {
            return $value;
        }

        if ($target === RegularBlock::class)
        {
            /** @var Statement|null $asStatement */
            $asStatement = self::tryConvert($value, Statement::class, $phpVersion);
            if ($asStatement)
            {
                return new RegularBlock($asStatement);
            }
        }

        if ($target === Statement::class)
        {
            /** @var Expression|null $asExpression */
            $asExpression = self::tryConvert($value, Expression::class, $phpVersion);
            if ($asExpression)
            {
                try
                {
                    $asExpression->validateContext(Expression::CTX_READ);
                    return new ExpressionStatement($asExpression);
                }
                catch (ValidationException $e)
                {
                    // conversion not applicable
                }
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
