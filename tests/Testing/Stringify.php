<?php

namespace Phi\Tests\Testing;

use Phi\Node;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Nodes\NumberLiteral;
use Phi\Token;
use ReflectionClass;

class Stringify
{
    public static function node(Node $node): string
    {
        if ($node instanceof NumberLiteral)
        {
            return $node->getToken()->getSource();
        }
        else if ($node instanceof CompoundNode)
        {
            $children = [];
            foreach ($node->childNodes() as $child)
            {
                if ($child)
                {
                    $children[] = self::node($child);
                }
            }
            return (new ReflectionClass($node))->getShortName() . '(' . implode(', ', $children) . ')';
        }
        else if ($node instanceof NodesList || $node instanceof SeparatedNodesList)
        {
            $items = [];
            foreach ($node->childNodes() as $item)
            {
                $items[] = self::node($item);
            }
            return '[' . implode(', ', $items) . ']';
        }
        else if ($node instanceof Token)
        {
            if (in_array($node->getType(), ['(', ')'], true))
            {
                return '`' . $node->getSource() . '`';
            }
            return $node->getSource();
        }
        else
        {
            throw new \RuntimeException(\is_object($node) ? \get_class($node) : \gettype($node));
        }
    }
}
