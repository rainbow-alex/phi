<?php

namespace Phi\Nodes\Base;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use Phi\Node;

class SeparatedNodesList extends Node implements IteratorAggregate
{
    /**
     * @var array<Node|null>
     */
    private $nodes = [];

    /**
     * @param array<Node|null> $nodes
     */
    public function __initUnchecked(array $nodes): void
    {
        $this->nodes = $nodes;
        foreach ($nodes as $n)
        {
            if ($n)
            {
                $n->parent = $this;
            }
        }
    }

    protected function detachChild(Node $childToDetach): void
    {
        $i = \array_search($childToDetach, $this->nodes, true);

        if ($i === false)
        {
            throw new \RuntimeException("$childToDetach is not attached to $this");
        }

        \array_splice($this->nodes, $i, 1);
    }

    public function childNodes(): array
    {
        return \array_values(\array_filter($this->nodes));
    }

    public function tokens(): iterable
    {
        foreach ($this->nodes as $node)
        {
            if ($node)
            {
                yield from $node->tokens();
            }
        }
    }

    public function getLeftWhitespace(): string
    {
        foreach ($this->nodes as $node)
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
        for ($i = count($this->nodes) - 1; $i >= 0; $i--)
        {
            $node = $this->nodes[$i];
            if ($node)
            {
                return $node->getRightWhitespace();
            }
        }
        return '';
    }

    public function _validate(int $flags): void
    {
        // TODO do only compound nodes need this?
        foreach ($this->nodes as $i => $node)
        {
            if ($node && $i % 2 === 1)
            {
                $node->_validate($flags);
            }
        }
    }

    public function toPhp(): string
    {
        $php = '';
        foreach ($this->nodes as $node)
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

        foreach ($this->nodes as $node)
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
        foreach ($this->nodes as $i => $node)
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
        foreach ($this->nodes as $i => $node)
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
        if (count($this->nodes) % 2 === 0)
        {
            $this->nodes[] = null;
        }

        $node->detach();
        $node->parent = $this;
        $this->nodes[] = $node;
    }
}
