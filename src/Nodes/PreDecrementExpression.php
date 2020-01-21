<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPreDecrementExpression;

class PreDecrementExpression extends GeneratedPreDecrementExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
