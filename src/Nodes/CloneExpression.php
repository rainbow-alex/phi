<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedCloneExpression;

class CloneExpression extends GeneratedCloneExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
