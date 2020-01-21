<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Base\ReadOnlyExpression;
use Phi\Nodes\Generated\GeneratedCallExpression;

class CallExpression extends GeneratedCallExpression
{
    use DynamicExpression;
    use ReadOnlyExpression;
    public function isAliasRead(): bool { return true; }
}
