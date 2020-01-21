<?php

namespace Phi\Nodes\Base;

use ArrayIterator;
use Phi\Node;
use Iterator;
use IteratorAggregate;

class SeparatedNodesList extends AbstractNode implements IteratorAggregate
{
    /**
     * @var array<Node|null>
     * @internal
     */
    public $_nodes = [];

    /**
     * @param array<Node|null> $nodes
     */
    public function __initUnchecked(array $nodes): void
    {
        $this->_nodes = $nodes;
    }

    public function _detachChild(Node $childToDetach): void
    {
        $i = \array_search($childToDetach, $this->_nodes, true);

        if ($i === false)
        {
            throw new \RuntimeException("$childToDetach is not attached to $this");
        }

        \array_splice($this->_nodes, $i, 1);
    }

    public function childNodes(): array
    {
        return \array_values(\array_filter($this->_nodes));
    }

    public function tokens(): iterable
    {
        foreach ($this->_nodes as $node)
        {
            if ($node)
            {
                yield from $node->tokens();
            }
        }
    }

    public function getLeftWhitespace(): string
    {
        foreach ($this->_nodes as $node)
        {
            if ($node)
            {
                return $node->getLeftWhitespace();
            }
        }
        return '';
    }

    public function getRightWhitespace(): string
    {
        for ($i = count($this->_nodes) - 1; $i >= 0; $i--)
        {
            $node = $this->_nodes[$i];
            if ($node)
            {
                return $node->getRightWhitespace();
            }
        }
        return '';
    }

    public function __toString(): string
    {
        $php = '';
        foreach ($this->_nodes as $node)
        {
            if ($node)
            {
                $php .= $node;
            }
        }
        return $php;
    }

    public function debugDump(string $indent = ''): void
    {
        echo $indent . $this->repr() . " [\n", null;

        foreach ($this->_nodes as $node)
        {
            if ($node)
            {
                $node->debugDump($indent . "    ");
            }
        }

        echo $indent . "]\n";
    }

    /** @return Iterator|Node[] */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->getItems());
    }

    /** @return Node[] */
    public function getItems(): array
    {
        $items = [];
        foreach ($this->_nodes as $i => $node)
        {
            if ($node && $i % 2 === 1)
            {
                $items[] = $node;
            }
        }
        return $items;
    }

    /** @return Node[] */
    public function getSeparators(): array
    {
        $separators = [];
        foreach ($this->_nodes as $i => $node)
        {
            if ($node && $i % 2 === 0)
            {
                $separators[] = $node;
            }
        }
        return $separators;
    }

    public function add(Node $node): void
    {
        if (count($this->_nodes) % 2 === 0)
        {
            $this->_nodes[] = null;
        }

        $node->_attachTo($this);
        $this->_nodes[] = $node;
    }
}
