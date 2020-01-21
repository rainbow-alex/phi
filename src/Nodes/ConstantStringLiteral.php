<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ConstantExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedConstantStringLiteral;

class ConstantStringLiteral extends GeneratedConstantStringLiteral
{
    use ConstantExpression;
    use ReadOnlyExpression;
}
