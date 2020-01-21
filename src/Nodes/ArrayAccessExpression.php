<?php

namespace Phi\Nodes;

use Phi\Nodes\Generated\GeneratedArrayAccessExpression;

class ArrayAccessExpression extends GeneratedArrayAccessExpression
{
    public function isConstant(): bool
    {
        $index = $this->getIndex();
        return $this->getAccessee()->isConstant() && $index && $index->isConstant();
    }

    public function isTemporary(): bool { return $this->getAccessee()->isTemporary(); }
    public function isRead(): bool { return $this->getIndex() !== null; }
    public function isReadOffset(): bool { return $this->getIndex() !== null; }
    public function isWrite(): bool { return !$this->getAccessee()->isTemporary(); }
    public function isAliasRead(): bool { return !$this->getAccessee()->isTemporary(); }
    public function isAliasWrite(): bool { return !$this->getAccessee()->isTemporary(); }
}
