<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPreIncrementExpression;

class PreIncrementExpression extends GeneratedPreIncrementExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
