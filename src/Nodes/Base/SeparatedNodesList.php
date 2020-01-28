<?php

namespace Phi\Nodes\Base;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use Phi\Node;
use Phi\Token;

/**
 * @template T of Node
 * @extends BaseListNode<T>
 * @implements IteratorAggregate<T>
 */
class SeparatedNodesList extends BaseListNode implements IteratorAggregate
{
    /**
     * @var array<Node|Token|null>
     * @phpstan-var array<T|Token|null>
     */
    private $nodes = [];

    /**
     * @param array<Node|Token|null> $nodes
     * @phpstan-param array<T|Token|null> $nodes
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
            throw new \RuntimeException($childToDetach . " is not attached to " . $this);
        }

        \array_splice($this->nodes, $i, 1);
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter($this->nodes));
    }

    public function iterTokens(): iterable
    {
        foreach ($this->nodes as $node)
        {
            if ($node)
            {
                yield from $node->tokens();
            }
        }
    }

    public function _validate(int $flags): void
    {
        // TODO do only compound nodes need this?
        foreach ($this->nodes as $i => $node)
        {
            if ($node && $i % 2 === 1)
            {
                /** @phpstan-var T $node */
                $node->_validate($flags);
            }
        }
    }

    public function toPhp(): string
    {
        $php = "";
        foreach ($this->nodes as $node)
        {
            if ($node)
            {
                $php .= $node;
            }
        }
        return $php;
    }

    public function debugDump(string $indent = ""): void
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

    /**
     * @return Iterator<Node>
     * @phpstan-return Iterator<T>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->getItems());
    }

    /**
     * @return Node[]
     * @phpstan-return T[]
     */
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
        /** @var T[] $items convince phpstan */
        return $items;
    }

    /** @return Token[] */
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
        /** @var array<Token> $separators convince phpstan */
        return $separators;
    }

    /** @param T $node */
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
