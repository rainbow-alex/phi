<?php

namespace Phi\Nodes\Base;

use Phi\Nodes\ArrayItem;

trait ArrayLiteralExpression // TODO base class?
{
    public function isConstant(): bool
    {
        foreach ($this->getItems() as $item)
        {
            /** @var ArrayItem $item */

            $key = $item->getKey();
            if ($key && !$key->getValue()->isConstant())
            {
                return false;
            }

            $value = $item->getValue();
            if (!$value || !$value->isConstant())
            {
                return false;
            }
        }

        return true;
    }

    public function isTemporary(): bool
    {
        return true;
    }

    public function isRead(): bool
    {
        foreach ($this->getItems() as $item)
        {
            /** @var ArrayItem $item */

            if (!$item->getValue())
            {
                return false;
            }
        }

        return true;
    }

    public function isReadOffset(): bool
    {
        return !$this->isConstant();
    }

    public function isWrite(): bool
    {
        foreach ($this->getItems() as $item)
        {
            /** @var ArrayItem $item */

            $value = $item->getValue();
            if ($value && !$value->isWrite())
            {
                return false;
            }
        }

        return true;
    }

    public function isAliasRead(): bool
    {
        return false;
    }

    public function isAliasWrite(): bool
    {
        return false;
    }
}
