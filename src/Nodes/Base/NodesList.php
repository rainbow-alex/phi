<?php

declare(strict_types=1);

namespace Phi\Nodes\Base;

use Countable;
use IteratorAggregate;
use Phi\Exception\TodoException;
use Phi\Exception\TreeException;
use Phi\Node;
use Phi\Util\Console;

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

	public function detach(): void
	{
		throw TreeException::cantDetachList($this);
	}

	protected function detachChild(Node $child): void
	{
		$i = \array_search($child, $this->nodes, true);

		if ($i === false)
		{
			throw new \RuntimeException($child . " is not attached to " . $this);
		}

		\array_splice($this->nodes, $i, 1);
		// TODO fix parent refs
	}

	protected function replaceChild(Node $child, Node $replacement): void
	{
		throw new TodoException();
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

	public function clone(): Node
	{
		$clone = parent::clone();
		$clone->nodes = [];
		foreach ($this->nodes as $child)
		{
			$childClone = $child->clone();
			$childClone->parent = $clone;
			$clone->nodes[] = $childClone;
		}
		return $clone;
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
			$php .= $node->toPhp();
		}
		return $php;
	}

	public function debugDump(string $indent = ""): void
	{
		echo Console::faint($this->repr()) . "\n";

		foreach ($this->nodes as $i => $v)
		{
			echo $indent . Console::green(\str_pad($i . ":", 4));
			$v->debugDump($indent . "    ");
		}
	}

	public function convertToPhpParser()
	{
		return \array_map(function (Node $n) { return $n->convertToPhpParser(); }, $this->nodes);
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
