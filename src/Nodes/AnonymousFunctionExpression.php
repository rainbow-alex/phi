<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedAnonymousFunctionExpression;

class AnonymousFunctionExpression extends GeneratedAnonymousFunctionExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
