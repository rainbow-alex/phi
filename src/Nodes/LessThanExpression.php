<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedLessThanExpression;

class LessThanExpression extends GeneratedLessThanExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
