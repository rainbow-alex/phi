<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\ConstantExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedClassNameResolutionExpression;

class ClassNameResolutionExpression extends GeneratedClassNameResolutionExpression
{
    use ConstantExpression;
    use ReadOnlyExpression;
}
