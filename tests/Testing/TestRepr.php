<?php

namespace Phi\Tests\Testing;

use Phi\Node;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Token;
use Phi\Nodes;

class TestRepr
{
    /** @param Token[] $tokens */
    public static function tokens(array $tokens): string
    {
        $types = [];
        foreach ($tokens as $t)
        {
            $types[] = Token::typeToString($t->getType());
        }
        return implode(' ', $types);
    }

    public static function node(Node $node): string
    {
        if ($node instanceof Nodes\IntegerLiteral)
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
            return $node->repr() . '(' . implode(', ', $children) . ')';
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
            if (in_array($node->getType(), [Token::PH_S_LEFT_PAREN, Token::PH_S_RIGHT_PAREN], true))
            {
                return '`' . $node->getSource() . '`';
            }
            return $node->getSource();
        }
        else
        {
            throw new \RuntimeException(\get_class($node));
        }
    }
}
