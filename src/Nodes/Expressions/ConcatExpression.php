<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedConcatExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Concat;

class ConcatExpression extends BinopExpression
{
    use GeneratedConcatExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Concat($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_ADD;
    }
}
