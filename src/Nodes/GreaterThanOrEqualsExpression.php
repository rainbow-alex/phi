<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedGreaterThanOrEqualsExpression;

class GreaterThanOrEqualsExpression extends GeneratedGreaterThanOrEqualsExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
