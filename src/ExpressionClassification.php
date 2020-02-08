<?php

declare(strict_types=1);

namespace Phi;

use Phi\Nodes;
use Phi\Nodes\Expression;

/**
 * PHP's parser does some additional checks on expressions to make sure they're not used in the wrong context.
 * Unfortunately the checks are rather unintuitive and arbitrary.
 * I've opted to put them here and make liberal use of instanceof instead of making these part of the Expression node API.
 *
 * @internal
 *
 * TODO: move these methods to traits, also for Yield(From)Expression
 */
class ExpressionClassification
{
    public static function isObjectAccessible(Expression $expression): bool
    {
        return !(
            $expression instanceof Nodes\Expressions\AnonymousFunctionExpression
            || $expression instanceof Nodes\Expressions\ClassNameResolutionExpression
            || $expression instanceof Nodes\Expressions\ConstantAccessExpression
            || $expression instanceof Nodes\Expressions\EmptyExpression
            || $expression instanceof Nodes\Expressions\EvalExpression
            || $expression instanceof Nodes\Expressions\ExecExpression
            || $expression instanceof Nodes\Expressions\ExitExpression
            || $expression instanceof Nodes\Expressions\IssetExpression
            || $expression instanceof Nodes\Expressions\MagicConstant
            || $expression instanceof Nodes\Expressions\NameExpression
            || $expression instanceof Nodes\Expressions\NewExpression
            || $expression instanceof Nodes\Expressions\NumberLiteral
        );
    }

    public static function isStaticAccessible(Expression $expression): bool
    {
        return !(
            $expression instanceof Nodes\Expressions\AnonymousFunctionExpression
            || $expression instanceof Nodes\Expressions\ClassNameResolutionExpression
            || $expression instanceof Nodes\Expressions\ConstantAccessExpression
            || $expression instanceof Nodes\Expressions\EmptyExpression
            || $expression instanceof Nodes\Expressions\EvalExpression
            || $expression instanceof Nodes\Expressions\ExecExpression
            || $expression instanceof Nodes\Expressions\ExitExpression
            || $expression instanceof Nodes\Expressions\IssetExpression
            || $expression instanceof Nodes\Expressions\MagicConstant
            || $expression instanceof Nodes\Expressions\NewExpression
            || $expression instanceof Nodes\Expressions\NumberLiteral
            || ($expression instanceof Nodes\Expressions\ArrayExpression && $expression->isConstant())
        );
    }

    public static function isNewable(Expression $expression): bool
    {
        if ($expression instanceof Nodes\Expressions\ArrayAccessExpression)
        {
            if ($expression->getAccessee() instanceof Nodes\Expressions\ArrayAccessExpression)
            {
                return self::isNewable($expression->getAccessee());
            }
            else
            {
                return $expression->getAccessee() instanceof Nodes\Expressions\VariableExpression;
            }
        }
        else
        {
            return (
                $expression instanceof Nodes\Expressions\PropertyAccessExpression
                || $expression instanceof Nodes\Expressions\StaticPropertyAccessExpression
                || $expression instanceof Nodes\Expressions\NameExpression
                || $expression instanceof Nodes\Expressions\VariableExpression
            );
        }
    }
}
