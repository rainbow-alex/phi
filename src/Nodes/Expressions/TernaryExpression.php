<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedTernaryExpression;
use PhpParser\Node\Expr\Ternary;

class TernaryExpression extends Expression
{
    use GeneratedTernaryExpression;

    public function isConstant(): bool
    {
        $then = $this->getIf();
        return $this->getCondition()->isConstant()
            && (!$then || $then->isConstant())
            && $this->getElse()->isConstant();
    }

    public function convertToPhpParserNode()
    {
        return new Ternary(
            $this->getCondition()->convertToPhpParserNode(),
            ($if = $this->getIf()) ? $if->convertToPhpParserNode() : null,
            $this->getElse()->convertToPhpParserNode()
        );
    }
}
