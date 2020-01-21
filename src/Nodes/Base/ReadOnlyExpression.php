<?php

namespace Phi\Nodes\Base;

trait ReadOnlyExpression
{
    public function isTemporary(): bool { return true; }
    public function isRead(): bool { return true; }
    public function isReadOffset(): bool { return true; }
    public function isWrite(): bool { return false; }
    public function isAliasRead(): bool { return false; }
    public function isAliasWrite(): bool { return false; }
}
