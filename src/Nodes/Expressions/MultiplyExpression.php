<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedMultiplyExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Mul;

class MultiplyExpression extends BinopExpression
{
    use GeneratedMultiplyExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Mul($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_MUL;
    }
}
