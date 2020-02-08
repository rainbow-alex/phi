<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions\StringInterpolation;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedInterpolatedArrayAccessIndex;
use Phi\TokenType;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;

class InterpolatedArrayAccessIndex extends CompoundNode
{
    use GeneratedInterpolatedArrayAccessIndex;

    public function convertToPhpParserNode()
    {
        $value = $this->getValue();
        if ($value->getType() === TokenType::T_STRING)
        {
            return new String_($value->getSource());
        }
        else if ($value->getType() === TokenType::T_NUM_STRING)
        {
            // TODO int parsing?
            return new LNumber(((int) $value->getSource()) * ($this->hasMinus() ? -1 : 1));
        }
        else
        {
            return new Variable(\substr($value->getSource(), 1));
        }
    }
}
