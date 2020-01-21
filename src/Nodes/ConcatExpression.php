<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedConcatExpression;

class ConcatExpression extends GeneratedConcatExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
