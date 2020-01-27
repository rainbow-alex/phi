<?php

namespace Phi\Nodes;

use Phi\Nodes\Context\PureBinopExpression;
use Phi\Nodes\Generated\GeneratedIsNotIdenticalExpression;

class IsNotIdenticalExpression extends GeneratedIsNotIdenticalExpression
{
    use PureBinopExpression;
}
