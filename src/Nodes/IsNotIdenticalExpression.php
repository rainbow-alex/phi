<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedIsNotIdenticalExpression;

class IsNotIdenticalExpression extends GeneratedIsNotIdenticalExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
