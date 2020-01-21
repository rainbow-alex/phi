<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ConstantExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedMagicConstant;

class MagicConstant extends GeneratedMagicConstant
{
    use ConstantExpression;
    use ReadOnlyExpression;
}
