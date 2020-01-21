<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedSpaceshipExpression;

class SpaceshipExpression extends GeneratedSpaceshipExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
