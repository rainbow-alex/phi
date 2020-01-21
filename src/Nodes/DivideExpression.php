<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedDivideExpression;

class DivideExpression extends GeneratedDivideExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
