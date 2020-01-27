<?php

namespace Phi;

use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Nodes as PhiNodes;
use PhpParser\Node as PPNodes;

class PhpParserCompat
{
    public static function convert(Node $node)
    {
        if ($node instanceof NodesList || $node instanceof SeparatedNodesList)
        {
            $items = $node->getItems();

            // flatten blocks
            for ($i = 0; $i < count($items); $i++)
            {
                while ($items[$i] instanceof PhiNodes\RegularBlock)
                {
                    \array_splice($items, $i, 1, $items[$i]->getStatements()->getItems());
                }
            }

            return array_map([self::class, 'convert'], $items);
        }
        else if ($node instanceof Token)
        {
            return $node->getSource();
        }

        else if ($node instanceof PhiNodes\RootNode)
        {
            return self::convert($node->getStatements());
        }

        //
        // Statements
        //
        else if ($node instanceof PhiNodes\EchoStatement)
        {
            return new PPNodes\Stmt\Echo_(self::convert($node->getExpressions()));
        }
        else if ($node instanceof PhiNodes\ExpressionStatement)
        {
            return new PPNodes\Stmt\Expression(self::convert($node->getExpression()));
        }

        //
        // Expressions
        //
        else if ($node instanceof PhiNodes\RegularAssignmentExpression)
        {
            return new PPNodes\Expr\Assign(self::convert($node->getLvalue()), self::convert($node->getValue()));
        }

        else if ($node instanceof PhiNodes\BitwiseAndExpression)
        {
            return new PPNodes\Expr\BinaryOp\BitwiseAnd(self::convert($node->getLeft()), self::convert($node->getRight()));
        }
        else if ($node instanceof PhiNodes\BitwiseOrExpression)
        {
            return new PPNodes\Expr\BinaryOp\BitwiseOr(self::convert($node->getLeft()), self::convert($node->getRight()));
        }
        else if ($node instanceof PhiNodes\BitwiseXorExpression)
        {
            return new PPNodes\Expr\BinaryOp\BitwiseXor(self::convert($node->getLeft()), self::convert($node->getRight()));
        }
        else if ($node instanceof PhiNodes\ShiftLeftExpression)
        {
            return new PPNodes\Expr\BinaryOp\ShiftLeft(self::convert($node->getLeft()), self::convert($node->getRight()));
        }
        else if ($node instanceof PhiNodes\ShiftRightExpression)
        {
            return new PPNodes\Expr\BinaryOp\ShiftRight(self::convert($node->getLeft()), self::convert($node->getRight()));
        }

        else if ($node instanceof PhiNodes\FunctionCallExpression)
        {
            $callee = $node->getCallee();
            if ($callee instanceof PhiNodes\NameExpression)
            {
                $callee = $callee->getName();
            }
            return new PPNodes\Expr\FuncCall(self::convert($callee), self::convert($node->getArguments()));
        }

        else if ($node instanceof PhiNodes\BooleanNotExpression)
        {
            return new PPNodes\Expr\BooleanNot(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\CastExpression)
        {
            switch ($node->getCast()->getType())
            {
                case Token::PH_T_ARRAY_CAST:
                    return new PPNodes\Expr\Cast\Array_(self::convert($node->getExpression()));
                case Token::PH_T_BOOL_CAST:
                    return new PPNodes\Expr\Cast\Bool_(self::convert($node->getExpression()));
                case Token::PH_T_INT_CAST:
                    return new PPNodes\Expr\Cast\Int_(self::convert($node->getExpression()));
                case Token::PH_T_OBJECT_CAST:
                    return new PPNodes\Expr\Cast\Object_(self::convert($node->getExpression()));
                default:
                    throw new \RuntimeException($node->getCast()->getType());
            }
        }
        else if ($node instanceof PhiNodes\SuppressErrorsExpression)
        {
            return new PPNodes\Expr\ErrorSuppress(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\PreDecrementExpression)
        {
            return new PPNodes\Expr\PreDec(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\PreIncrementExpression)
        {
            return new PPNodes\Expr\PreInc(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\PostDecrementExpression)
        {
            return new PPNodes\Expr\PostDec(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\PostIncrementExpression)
        {
            return new PPNodes\Expr\PostInc(self::convert($node->getExpression()));
        }
        else if ($node instanceof PhiNodes\IncludeLikeExpression)
        {
            $type = [
                Token::PH_T_INCLUDE => PPNodes\Expr\Include_::TYPE_INCLUDE,
                Token::PH_T_INCLUDE_ONCE => PPNodes\Expr\Include_::TYPE_INCLUDE_ONCE,
                Token::PH_T_REQUIRE => PPNodes\Expr\Include_::TYPE_REQUIRE,
                Token::PH_T_REQUIRE_ONCE => PPNodes\Expr\Include_::TYPE_REQUIRE_ONCE,
            ][$node->getKeyword()->getType()];
            return new PPNodes\Expr\Include_(self::convert($node->getExpression()), $type);
        }

        else if ($node instanceof PhiNodes\IssetExpression)
        {
            $expressions = \iterator_to_array($node->getExpressions());
            return new PPNodes\Expr\Isset_(\array_map([self::class, 'convert'], $expressions));
        }
        else if ($node instanceof PhiNodes\ShortArrayExpression) // TODO use interface to handle all shapes?
        {
            $items = \iterator_to_array($node->getItems());
            return new PPNodes\Expr\Array_(\array_map([self::class, 'convert'], $items));
        }
        else if ($node instanceof PhiNodes\RegularVariableExpression)
        {
            return new PPNodes\Expr\Variable(substr($node->getVariable()->getSource(), 1));
        }
        else if ($node instanceof PhiNodes\NameExpression)
        {
            return new PPNodes\Expr\ConstFetch(self::convert($node->getName()));
        }
        else if ($node instanceof PhiNodes\ConstantStringLiteral)
        {
            return new PPNodes\Scalar\String_(PPNodes\Scalar\String_::parse($node)); // TODO unicode test
        }
        else if ($node instanceof PhiNodes\IntegerLiteral)
        {
            return new PPNodes\Scalar\LNumber($node->getValue());
        }
        else if ($node instanceof PhiNodes\FloatLiteral)
        {
            return new PPNodes\Scalar\DNumber($node->getValue());
        }

        //
        // Helpers
        //
        else if ($node instanceof PhiNodes\RegularBlock)
        {
            return self::convert($node->getStatements());
        }
        else if ($node instanceof PhiNodes\RegularName)
        {
            return new PPNodes\Name(self::convert($node->getParts()));
        }
        else if ($node instanceof PhiNodes\Argument)
        {
            return new PPNodes\Arg(self::convert($node->getExpression()),false, $node->hasUnpack());
        }
        else if ($node instanceof PhiNodes\ArrayItem)
        {
            if (!$node->getValue())
            {
                return null;
            }
            else
            {
                return new PPNodes\Expr\ArrayItem(
                    self::convert($node->getValue()),
                    $node->getKey() ? self::convert($node->getKey()->getValue()) : null,
                    $node->hasByReference()
                );
            }
        }

        else
        {
            throw new \RuntimeException(\get_class($node));
        }
    }
}
