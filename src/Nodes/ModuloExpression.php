<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\BinopExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedModuloExpression;

class ModuloExpression extends GeneratedModuloExpression
{
    use BinopExpression;
    use ReadOnlyExpression;
}
