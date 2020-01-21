<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedGreaterThanExpression;

class GreaterThanExpression extends GeneratedGreaterThanExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
