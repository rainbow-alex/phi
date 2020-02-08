<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Exception\ValidationException;
use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNameExpression;
use PhpParser\Node\Expr\ConstFetch;

class NameExpression extends Expression
{
    use GeneratedNameExpression;

    public function isConstant(): bool
    {
        return true;
    }

    protected function extraValidation(int $flags): void
    {
        if ($this->getName()->isStatic())
        {
            if (!(
                $this->parent instanceof ConstantAccessExpression
                || $this->parent instanceof StaticPropertyAccessExpression
                || $this->parent instanceof StaticMethodCallExpression
                || $this->parent instanceof NewExpression
            ))
            {
                throw ValidationException::invalidNameInContext($this->getName());
            }
        }
    }

    public function convertToPhpParserNode()
    {
        return new ConstFetch($this->getName()->convertToPhpParserNode());
    }
}
