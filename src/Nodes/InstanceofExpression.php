<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedInstanceofExpression;

class InstanceofExpression extends GeneratedInstanceofExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
