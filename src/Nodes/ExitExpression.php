<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedExitExpression;

class ExitExpression extends GeneratedExitExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
