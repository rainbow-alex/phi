<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedArrayAccessExpression;
use PhpParser\Node\Expr\ArrayDimFetch;

class ArrayAccessExpression extends Expression
{
    use GeneratedArrayAccessExpression;

    public function isConstant(): bool
    {
        // TODO rename accessee
        $index = $this->getIndex();
        return $this->getAccessee()->isConstant() && $index && $index->isConstant();
    }

    public function isTemporary(): bool
    {
        return false;
    }

    protected function extraValidation(int $flags): void
    {
        if (
            $this->getAccessee() instanceof NewExpression
            || $this->getAccessee() instanceof ExitExpression
            || $this->getAccessee() instanceof EmptyExpression
            || $this->getAccessee() instanceof EvalExpression
            || $this->getAccessee() instanceof ExecExpression
            || $this->getAccessee() instanceof IssetExpression
            || $this->getAccessee() instanceof MagicConstant
            || $this->getAccessee() instanceof AnonymousFunctionExpression
            || $this->getAccessee() instanceof NumberLiteral
        )
        {
            throw ValidationException::invalidSyntax($this->getLeftBracket());
        }

        if ($flags & self::CTX_WRITE && $this->getAccessee()->isTemporary())
        {
            throw ValidationException::invalidExpression($this, $this->getLeftBracket());
        }

        if (!$this->index)
        {
            if ($flags & self::CTX_READ)
            {
                // there are some exceptions where $a[] is allowed even though it isn't usually considered read
                if (!(
                    $flags & self::CTX_READ_IMPLICIT_BY_REF // foo($a[]) is allowed
                    || $flags & self::CTX_WRITE // $a[]++ is allowed
                ))
                {
                    throw ValidationException::invalidExpressionInContext($this, $this->leftBracket);
                }
            }
        }
    }

    public function convertToPhpParserNode()
    {
        $index = $this->getIndex();
        return new ArrayDimFetch(
            $this->getAccessee()->convertToPhpParserNode(),
            $index ? $index->convertToPhpParserNode() : null
        );
    }
}
