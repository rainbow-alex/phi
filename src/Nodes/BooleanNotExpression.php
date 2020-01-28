<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureUnaryExpression;
use Phi\Nodes\Generated\GeneratedBooleanNotExpression;
use PhpParser\Node\Expr\BooleanNot;

class BooleanNotExpression extends GeneratedBooleanNotExpression
{
    use PureUnaryExpression;

    public function convertToPhpParserNode()
    {
        return new BooleanNot($this->getExpression()->convertToPhpParserNode());
    }
}
