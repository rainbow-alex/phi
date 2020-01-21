<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPrintExpression;

class PrintExpression extends GeneratedPrintExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
}
