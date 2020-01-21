<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedSuppressErrorsExpression;

class SuppressErrorsExpression extends GeneratedSuppressErrorsExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
