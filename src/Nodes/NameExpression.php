<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedNameExpression;

class NameExpression extends GeneratedNameExpression
{
    use DynamicExpression; // TODO verify
    use ReadOnlyExpression;
}
