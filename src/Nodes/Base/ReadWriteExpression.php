<?php

namespace Phi\Nodes\Base;

trait ReadWriteExpression
{
    public function isTemporary(): bool { return false; }
    public function isRead(): bool { return true; }
    public function isReadOffset(): bool { return true; }
    public function isWrite(): bool { return true; }
    public function isAliasRead(): bool { return true; }
    public function isAliasWrite(): bool { return true; }
}
