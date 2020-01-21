<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedBitwiseAndExpression;

class BitwiseAndExpression extends GeneratedBitwiseAndExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
