<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadWriteExpression;
use Phi\Nodes\Generated\GeneratedStaticPropertyAccessExpression;

class StaticPropertyAccessExpression extends GeneratedStaticPropertyAccessExpression
{
    use DynamicExpression;
    use ReadWriteExpression;
}
