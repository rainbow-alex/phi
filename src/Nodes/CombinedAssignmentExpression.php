<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedCombinedAssignmentExpression;

class CombinedAssignmentExpression extends GeneratedCombinedAssignmentExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}