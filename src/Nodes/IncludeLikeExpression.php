<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIncludeLikeExpression;

class IncludeLikeExpression extends GeneratedIncludeLikeExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
