<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPostIncrementExpression;

class PostIncrementExpression extends GeneratedPostIncrementExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
