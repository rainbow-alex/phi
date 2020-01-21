<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedLessThanOrEqualsExpression;

class LessThanOrEqualsExpression extends GeneratedLessThanOrEqualsExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
