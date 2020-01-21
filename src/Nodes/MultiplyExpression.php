<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedMultiplyExpression;

class MultiplyExpression extends GeneratedMultiplyExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
