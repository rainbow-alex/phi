<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedEmptyExpression;

class EmptyExpression extends GeneratedEmptyExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
