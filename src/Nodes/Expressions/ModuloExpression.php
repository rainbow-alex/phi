<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Generated\GeneratedModuloExpression;
use Phi\Nodes\ValidationTraits\LeftAssocBinopExpression;
use PhpParser\Node\Expr\BinaryOp\Mod;

class ModuloExpression extends BinopExpression
{
    use GeneratedModuloExpression;
    use LeftAssocBinopExpression;

    public function convertToPhpParserNode()
    {
        return new Mod($this->getLeft()->convertToPhpParserNode(), $this->getRight()->convertToPhpParserNode());
    }

    public function getPrecedence(): int
    {
        return self::PRECEDENCE_MUL;
    }
}
