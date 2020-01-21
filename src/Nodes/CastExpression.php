<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedCastExpression;

class CastExpression extends GeneratedCastExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
