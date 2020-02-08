<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedKeywordXorExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;

class KeywordXorExpression extends BinopExpression
{
    use GeneratedKeywordXorExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new LogicalXor($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_KEYWORD_XOR;
    }
}
