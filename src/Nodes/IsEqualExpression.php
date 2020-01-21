<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIsEqualExpression;

class IsEqualExpression extends GeneratedIsEqualExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
