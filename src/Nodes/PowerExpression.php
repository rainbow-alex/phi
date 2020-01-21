<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedPowerExpression;

class PowerExpression extends GeneratedPowerExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
