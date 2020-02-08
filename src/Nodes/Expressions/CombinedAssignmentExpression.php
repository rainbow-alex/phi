<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Generated\GeneratedCombinedAssignmentExpression;
use Phi\TokenType;
use PhpParser\Node\Expr\AssignOp;

class CombinedAssignmentExpression extends BinopExpression
{
    use GeneratedCombinedAssignmentExpression;

    public function isConstant(): bool
    {
        return false;
    }

    protected function extraValidation(int $flags): void
    {
        if ($this->getLeft()->isTemporary())
        {
            throw ValidationException::invalidExpressionInContext($this->getLeft());
        }
    }

    public function convertToPhpParserNode()
    {
        $left = $this->getLeft()->convertToPhpParserNode();
        $right = $this->getRight()->convertToPhpParserNode();

        switch ($this->getOperator()->getType())
        {
            case TokenType::T_AND_EQUAL: return new AssignOp\BitwiseAnd($left, $right);
            case TokenType::T_OR_EQUAL: return new AssignOp\BitwiseOr($left, $right);
            case TokenType::T_PLUS_EQUAL: return new AssignOp\Plus($left, $right);
            case TokenType::T_MINUS_EQUAL: return new AssignOp\Minus($left, $right);
            case TokenType::T_CONCAT_EQUAL: return new AssignOp\Concat($left, $right);
            case TokenType::T_MUL_EQUAL: return new AssignOp\Mul($left, $right);
            case TokenType::T_DIV_EQUAL: return new AssignOp\Div($left, $right);
            case TokenType::T_MOD_EQUAL: return new AssignOp\Mod($left, $right);
            case TokenType::T_POW_EQUAL: return new AssignOp\Pow($left, $right);
            case TokenType::T_SL_EQUAL: return new AssignOp\ShiftLeft($left, $right);
            case TokenType::T_SR_EQUAL: return new AssignOp\ShiftRight($left, $right);
            case TokenType::T_XOR_EQUAL: return new AssignOp\BitwiseXor($left, $right);
            default:
                throw new \LogicException();
        }
    }
}
