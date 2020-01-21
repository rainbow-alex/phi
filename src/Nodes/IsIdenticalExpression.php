<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIsIdenticalExpression;

class IsIdenticalExpression extends GeneratedIsIdenticalExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
