<?php

declare(strict_types=1);

namespace Phi\Nodes\Base;

use Countable;
use IteratorAggregate;
use Phi\Exception\TodoException;
use Phi\Node;
use Phi\Token;

/**
 * @template T of Node
 * @implements IteratorAggregate<T>
 */
class SeparatedNodesList extends Node implements Countable, IteratorAggregate
{
    /**
     * @var string
     * @phpstan-var class-string<T>
     */
    private $type;

    /**
     * @var array<Node|Token|null>
     * @phpstan-var array<T|Token|null>
     */
    private $nodes = [];

    /**
     * @var Node[]
     * @phpstan-var T[]
     */
    private $items = [];

    /**
     * @phpstan-param class-string<T> $type
     */
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param array<Node|Token|null> $nodes
     * @phpstan-param array<T|Token|null> $nodes
     */
    public function __initUnchecked(array $nodes): void
    {
        if (\count($nodes) % 2 === 1)
        {
            $nodes[] = null;
        }

        $this->nodes = $nodes;

        foreach ($nodes as $i => $n)
        {
            if ($i % 2 === 0)
            {
                /** @phpstan-var T $nAsT */
                $nAsT = $n;
                $this->items[] = $nAsT;
            }

            if ($n)
            {
                $n->parent = $this;
            }
        }
    }

    protected function detachChild(Node $childToDetach): void
    {
        throw new TodoException();
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
                yield from $node->iterTokens();
            }
        }
    }

    public function validate(): void
    {
        foreach ($this->items as $item)
        {
            $item->validate();
        }

        parent::validate();
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
            else
            {
                echo $indent . "~\n";
            }
        }

        echo $indent . "]\n";
    }

    public function convertToPhpParserNode()
    {
        return \array_map(function (Node $n) { return $n->convertToPhpParserNode(); }, $this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }

    /**
     * @return \Iterator<Node>
     * @phpstan-return \Iterator<T>
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * @return Node[]
     * @phpstan-return T[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /** @return array<Token|null> */
    public function getSeparators(): array
    {
        $separators = [];
        foreach ($this->nodes as $i => $node)
        {
            if ($node && $i % 2 === 1)
            {
                $separators[] = $node;
            }
        }
        /** @var array<Token|null> $separators convince phpstan */
        return $separators;
    }

    /**
     * @phpstan-param T $node
     */
    public function add(Node $node, Token $separator = null): void
    {
        if (!$node instanceof $this->type)
        {
            throw new \InvalidArgumentException("Added item should be of type " . $this->type);
        }

        $node->detach();
        $node->parent = $this;
        $this->items[] = $node;
        $this->nodes[] = $node;

        if ($separator)
        {
            $separator->detach();
            $separator->parent = $this;
        }
        $this->nodes[] = $separator;
    }

    public function hasTrailingSeparator(): bool
    {
        return $this->nodes && end($this->nodes) !== null;
    }
}
