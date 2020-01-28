<?php

namespace Phi\Nodes\Base;

use Phi\Node;
use Phi\Nodes\BlockStatement;

/**
 * @template T of Node
 */
abstract class BaseListNode extends Node
{
    /**
     * @return Node[]
     * @phpstan-return T[]
     */
    abstract public function getItems(): array;

    public function convertToPhpParserNode()
    {
        $items = $this->getItems();

        // TODO I think this can be done in Block, also handle NopStatement
        for ($i = 0; $i < count($items); $i++)
        {
            $item = $items[$i];

            // flatten blocks
            while ($item instanceof BlockStatement)
            {
                \array_splice($items, $i, 1, $item->getBlock()->getStatements()->getItems());
            }

            $items[$i] = $item->convertToPhpParserNode();
        }

        return $items;
    }
}
