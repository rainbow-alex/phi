<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedDivideExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Div;

class DivideExpression extends BinopExpression
{
    use GeneratedDivideExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Div($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_MUL;
    }
}
