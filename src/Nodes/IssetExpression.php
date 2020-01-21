<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIssetExpression;

class IssetExpression extends GeneratedIssetExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
