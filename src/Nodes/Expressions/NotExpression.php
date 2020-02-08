<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedNotExpression;
use PhpParser\Node\Expr\BooleanNot;

class NotExpression extends Expression
{
    use GeneratedNotExpression;

    public function isConstant(): bool
    {
        return $this->getExpression()->isConstant();
    }

    public function convertToPhpParserNode()
    {
        return new BooleanNot($this->getExpression()->convertToPhpParserNode());
    }
}
