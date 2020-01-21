<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedAliasingExpression;

class AliasingExpression extends GeneratedAliasingExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
