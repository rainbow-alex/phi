<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedYieldExpression;

class YieldExpression extends GeneratedYieldExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
