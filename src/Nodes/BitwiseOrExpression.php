<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedBitwiseOrExpression;

class BitwiseOrExpression extends GeneratedBitwiseOrExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
