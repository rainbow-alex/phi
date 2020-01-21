<?php

namespace Phi\Nodes;

use Phi\Node;

interface Expression extends Node
{
    public function isConstant(): bool;
    public function isTemporary(): bool;
    public function isRead(): bool;
    public function isReadOffset(): bool;
    public function isWrite(): bool;
    public function isAliasRead(): bool;
    public function isAliasWrite(): bool;
}
