<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedAddExpression;

class AddExpression extends GeneratedAddExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
