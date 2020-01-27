<?php

namespace Phi;

use Phi\Nodes\AnonymousFunctionExpression;
use Phi\Nodes\ArrayAccessExpression;
use Phi\Nodes\ConstantAccessExpression;
use Phi\Nodes\ConstantStringLiteral;
use Phi\Nodes\ExitExpression;
use Phi\Nodes\Expression;
use Phi\Nodes\InstanceofExpression;
use Phi\Nodes\NameExpression;
use Phi\Nodes\NewExpression;
use Phi\Nodes\NumberLiteral;
use Phi\Nodes\PropertyAccessExpression;
use Phi\Nodes\ShortArrayExpression;
use Phi\Nodes\StaticPropertyAccessExpression;
use Phi\Nodes\Variable;

/**
 * PHP's parser does some additional checks on expressions to make sure they're not used in the wrong context.
 * Unfortunately the checks are rather unintuitive and arbitrary.
 * I've opted to put them here and make liberal use of instanceof instead of making these part of the Expression node API.
 */
class ExpressionClassification
{
    public static function isConstant(Expression $expression): bool
    {
        if ($expression instanceof ConstantAccessExpression)
        {
            return self::isConstant($expression->getAccessee());
        }
        else if (
            $expression instanceof NameExpression
            || $expression instanceof ConstantStringLiteral
            || $expression instanceof NumberLiteral
        )
        {
            return true;
        }
        else if ($expression instanceof ShortArrayExpression)
        {
            foreach ($expression->getItems() as $item)
            {
                if ($key = $item->getKey())
                {
                    if (!self::isConstant($key->getValue()))
                    {
                        return false;
                    }
                }

                if ($item->hasByReference())
                {
                    return false;
                }

                if ($value = $item->getValue())
                {
                    if (!self::isConstant($value))
                    {
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    public static function isTemporary(Expression $expression): bool
    {
        if ($expression instanceof ArrayAccessExpression)
        {
            return self::isTemporary($expression->getAccessee());
        }
        else if (
            $expression instanceof PropertyAccessExpression
            || $expression instanceof StaticPropertyAccessExpression
            || $expression instanceof Variable
        )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function isCallable(Expression $expression): bool
    {
        if (
            $expression instanceof NewExpression
            || $expression instanceof ExitExpression
            || $expression instanceof NumberLiteral
            || $expression instanceof AnonymousFunctionExpression
            // note: the parser can't generate these combinations, but they're still not valid
            // their semantics are different from the code they generate
            // TODO test coverage
            || $expression instanceof PropertyAccessExpression
            || $expression instanceof StaticPropertyAccessExpression
            || $expression instanceof ConstantAccessExpression
        )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function isArrayAccessible(Expression $expression): bool
    {
        if (
            $expression instanceof NewExpression
            || $expression instanceof ExitExpression
            || $expression instanceof AnonymousFunctionExpression
            || $expression instanceof NumberLiteral
        )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /** @see InstanceofExpression */
    public static function isObject(Expression $expression): bool
    {
        if ($expression instanceof ShortArrayExpression)
        {
            // this is apparently part of the logic...
            // if an array *literal* is dynamic in any way, PHP's parser considers it might evaluate into something instanceof-able...
            return !self::isConstant($expression);
        }
        else if (
            $expression instanceof ConstantStringLiteral
            || $expression instanceof ExitExpression
            || $expression instanceof NumberLiteral
        )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // TODO static variable member access (::$foo or ::{expr}) isn't newable
    public static function isNewable(Expression $expression): bool
    {
        if (
            $expression instanceof PropertyAccessExpression
            || $expression instanceof StaticPropertyAccessExpression
            || $expression instanceof NameExpression
            || $expression instanceof Variable
        )
        {
            return true;
        }
        else if ($expression instanceof ArrayAccessExpression)
        {
            if ($expression->getAccessee() instanceof ArrayAccessExpression)
            {
                return self::isNewable($expression->getAccessee());
            }
            else
            {
                return $expression->getAccessee() instanceof Variable;
            }
        }
        else
        {
            // TODO note how this is important for call expressions, verify with test coverage!
            return false;
        }
    }

    public static function isKey(Expression $expression): bool
    {
        if ($expression instanceof ShortArrayExpression)
        {
            return !self::isConstant($expression);
        }
        else
        {
            return true;
        }
    }
}
