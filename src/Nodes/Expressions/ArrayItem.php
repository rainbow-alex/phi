<?php

declare(strict_types=1);

namespace Phi\Nodes\Expressions;

use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Generated\GeneratedArrayItem;

class ArrayItem extends CompoundNode
{
    use GeneratedArrayItem;

    public function convertToPhpParserNode()
    {
        $key = $this->getKey();
        $value = $this->getValue();
        if (!$value)
        {
            return null;
        }
        return new \PhpParser\Node\Expr\ArrayItem(
            $value->convertToPhpParserNode(),
            $key ? $key->getExpression()->convertToPhpParserNode() : null,
            $this->hasByReference(),
            [],
            false
        );
    }
}
