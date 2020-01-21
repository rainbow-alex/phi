<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedNewExpression;

class NewExpression extends GeneratedNewExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
