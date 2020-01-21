<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedEvalExpression;

class EvalExpression extends GeneratedEvalExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
