<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ConstantExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedNumberLiteral;

class NumberLiteral extends GeneratedNumberLiteral
{
    use ConstantExpression;
    use ReadOnlyExpression;
}
