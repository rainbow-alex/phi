<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedConcatExpression;

class ConcatExpression extends GeneratedConcatExpression
{
    use PureBinopExpression;
}
