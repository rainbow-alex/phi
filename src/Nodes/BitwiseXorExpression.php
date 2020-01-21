<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedBitwiseXorExpression;

class BitwiseXorExpression extends GeneratedBitwiseXorExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
