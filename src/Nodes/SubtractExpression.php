<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedSubtractExpression;

class SubtractExpression extends GeneratedSubtractExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
