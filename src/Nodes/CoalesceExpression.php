<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedCoalesceExpression;

class CoalesceExpression extends GeneratedCoalesceExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
