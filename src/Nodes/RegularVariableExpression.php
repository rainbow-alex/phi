<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadWriteExpression;
use Phi\Nodes\Generated\GeneratedRegularVariableExpression;

class RegularVariableExpression extends GeneratedRegularVariableExpression
{
    use DynamicExpression;
    use ReadWriteExpression;
}
