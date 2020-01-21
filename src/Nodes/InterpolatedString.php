<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedInterpolatedString;

class InterpolatedString extends GeneratedInterpolatedString
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
