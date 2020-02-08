<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Expression;
use Phi\Nodes\Generated\GeneratedCloneExpression;
use PhpParser\Node\Expr\Clone_;

class CloneExpression extends Expression
{
    use GeneratedCloneExpression;

    public function convertToPhpParserNode()
    {
        return new Clone_($this->getExpression()->convertToPhpParserNode());
    }
}
