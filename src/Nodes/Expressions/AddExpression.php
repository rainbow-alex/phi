<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedAddExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Plus;

class AddExpression extends BinopExpression
{
    use GeneratedAddExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Plus($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_ADD;
    }
}
