<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedRegularAssignmentExpression;

class RegularAssignmentExpression extends GeneratedRegularAssignmentExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
