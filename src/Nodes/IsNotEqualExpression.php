<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIsNotEqualExpression;

class IsNotEqualExpression extends GeneratedIsNotEqualExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
