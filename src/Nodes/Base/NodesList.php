<?php

declare(strict_types=1);

namespace Phi\Nodes\Base;

use Countable;
use IteratorAggregate;
use Phi\Node;

/**
 * @template T of Node
 * @implements IteratorAggregate<T>
 */
class NodesList extends Node implements Countable, IteratorAggregate
{
    /**
     * @var string
     * @phpstan-var class-string<T>
     */
    private $type;

    /**
     * @var Node[]
     * @phpstan-var T[]
     */
    private $nodes = [];

    /**
     * @phpstan-param class-string<T> $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param Node[] $nodes
     * @phpstan-param T[] $nodes
     */
    public function __initUnchecked(array $nodes): void
    {
        $this->nodes = $nodes;
        foreach ($nodes as $n)
        {
            $n->parent = $this;
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

    /**
     * @phpstan-return T[]
     */
    public function getChildNodes(): array
    {
        return $this->nodes;
    }

    public function iterTokens(): iterable
    {
        foreach ($this->nodes as $node)
        {
            yield from $node->iterTokens();
        }
    }

    public function validate(): void
    {
        foreach ($this->nodes as $node)
        {
            $node->validate();
        }

        parent::validate();
    }

    public function toPhp(): string
    {
        $php = "";
        foreach ($this->nodes as $node)
        {
            $php .= $node;
        }
        return $php;
    }

    public function debugDump(string $indent = ""): void
    {
        echo $indent . $this->repr() . " [\n";

        foreach ($this->nodes as $k => $v)
        {
            $v->debugDump($indent . "    ");
        }

        echo $indent . "]\n";
    }

    public function convertToPhpParserNode()
    {
        return \array_map(function (Node $n) { return $n->convertToPhpParserNode(); }, $this->nodes);
    }

    public function count(): int
    {
        return count($this->nodes);
    }

    /**
     * @return \Iterator<Node>
     * @phpstan-return \Iterator<T>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * @return Node[]
     * @phpstan-return T[]
     */
    public function getItems(): array
    {
        return $this->nodes;
    }

    /**
     * @phpstan-param T $node
     */
    public function add(Node $node): void
    {
        if (!$node instanceof $this->type)
        {
            throw new \InvalidArgumentException("Added item should be of type " . $this->type);
        }

        $this->nodes[] = $node;
    }
}
