<?php

namespace Phi\Nodes\Base;

use ArrayIterator;
use Countable;
use Phi\Node;
use Iterator;
use IteratorAggregate;

class NodesList extends AbstractNode implements Countable, IteratorAggregate
{
    /**
     * @var Node[]
     * @internal
     */
    public $_nodes = [];

    /**
     * @param Node[] $nodes
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
        return $this->_nodes;
    }

    public function tokens(): iterable
    {
        foreach ($this->_nodes as $node)
        {
            yield from $node->tokens();
        }
    }

    public function getLeftWhitespace(): string
    {
        return $this->_nodes ? $this->_nodes[0]->getLeftWhitespace() : '';
    }

    public function getRightWhitespace(): string
    {
        return $this->_nodes ? $this->_nodes[count($this->_nodes) - 1]->getRightWhitespace() : '';
    }

    public function __toString(): string
    {
        $php = '';
        foreach ($this->_nodes as $node)
        {
            $php .= $node;
        }
        return $php;
    }

    public function debugDump(string $indent = ''): void
    {
        echo $indent . $this->repr() . " [\n";

        foreach ($this->_nodes as $k => $v)
        {
            $v->debugDump($indent . "    ");
        }

        echo $indent . "]\n";
    }

    public function count(): int
    {
        return count($this->_nodes);
    }

    /** @return Iterator|Node[] */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->_nodes);
    }

    /** @return Node[] */
    public function getItems(): array
    {
        return $this->_nodes;
    }

    public function add(Node $node): void
    {
        $this->_nodes[] = $node;
    }
}
