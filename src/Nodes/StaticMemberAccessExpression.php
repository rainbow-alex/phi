<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ConstantExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedStaticMemberAccessExpression;

class StaticMemberAccessExpression extends GeneratedStaticMemberAccessExpression
{
    use ConstantExpression;
    use ReadOnlyExpression;
}
