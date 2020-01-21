<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedUnsetExpression;

class UnsetExpression extends GeneratedUnsetExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
