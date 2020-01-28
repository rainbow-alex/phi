<?php

namespace Phi\Nodes\Base;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;
use Phi\Node;

/**
 * @template T of Node
 * @extends BaseListNode<T>
 * @implements IteratorAggregate<T>
 */
class NodesList extends BaseListNode implements Countable, IteratorAggregate
{
    /**
     * @var Node[]
     * @phpstan-var T[]
     * @internal
     */
    public $_nodes = [];

    /**
     * @param Node[] $nodes
     * @phpstan-param T[] $nodes
     */
    public function __initUnchecked(array $nodes): void
    {
        $this->_nodes = $nodes;
        foreach ($nodes as $n)
        {
            $n->parent = $this;
        }
    }

    protected function detachChild(Node $childToDetach): void
    {
        $i = \array_search($childToDetach, $this->_nodes, true);

        if ($i === false)
        {
            throw new \RuntimeException($childToDetach . " is not attached to " . $this);
        }

        \array_splice($this->_nodes, $i, 1);
    }

    public function getChildNodes(): array
    {
        return $this->_nodes;
    }

    public function iterTokens(): iterable
    {
        foreach ($this->_nodes as $node)
        {
            yield from $node->iterTokens();
        }
    }

    protected function _validate(int $flags): void
    {
        foreach ($this->_nodes as $node)
        {
            $node->_validate($flags);
        }
    }

    public function toPhp(): string
    {
        $php = "";
        foreach ($this->_nodes as $node)
        {
            $php .= $node;
        }
        return $php;
    }

    public function debugDump(string $indent = ""): void
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

    /**
     * @return Iterator|Node[]
     * @phpstan-return Iterator<T>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->_nodes);
    }

    /**
     * @return Node[]
     * @phpstan-return T[]
     */
    public function getItems(): array
    {
        return $this->_nodes;
    }

    /**
     * @phpstan-param T $node
     */
    public function add(Node $node): void
    {
        $this->_nodes[] = $node;
    }
}
