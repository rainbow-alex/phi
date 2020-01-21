<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPostDecrementExpression;

class PostDecrementExpression extends GeneratedPostDecrementExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
