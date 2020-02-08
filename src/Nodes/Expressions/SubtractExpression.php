<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedSubtractExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Minus;

class SubtractExpression extends BinopExpression
{
    use GeneratedSubtractExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Minus($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_ADD;
    }
}
