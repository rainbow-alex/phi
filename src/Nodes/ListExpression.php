<?php

namespace Phi\Nodes;

use Phi\Nodes\Base\DynamicExpression;
use Phi\Nodes\Generated\GeneratedListExpression;

class ListExpression extends GeneratedListExpression
{
    use DynamicExpression;
    public function isTemporary(): bool { return true; }
    public function isRead(): bool { return false; }
    public function isReadOffset(): bool { return false; }
    public function isWrite(): bool { return true; }
    public function isAliasRead(): bool { return false; }
    public function isAliasWrite(): bool { return false; }
}
