<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedCoalesceExpression;

class CoalesceExpression extends GeneratedCoalesceExpression
{
    use PureBinopExpression;
}
