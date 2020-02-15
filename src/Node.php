<?php

declare(strict_types=1);

namespace Phi;

use Phi\Exception\PhiException;
use Phi\Exception\TreeException;
use Phi\Exception\ValidationException;

abstract class Node
{
	/**
	 * @var int|null
	 * @see PhpVersion
	 */
	protected $_phpVersion;
	/**
	 * @var Node|null
	 * @internal
	 */
	protected $parent;

	public function getPhpVersion(): int
	{
		return $this->getRoot()->_phpVersion ?? \PHP_VERSION_ID;
	}

	public function setPhpVersion(int $version): void
	{
		PhpVersion::validate($version);

		if ($this->parent)
		{
			throw new TreeException("phpVersion can only be set on the root node", $this);
		}

		$this->_phpVersion = $version;
	}

	public function getParent(): ?Node
	{
		return $this->parent;
	}

	public function getRoot(): Node
	{
		$node = $this;
		while ($node->parent)
		{
			$node = $node->parent;
		}
		return $node;
	}

	public function isAttached(): bool
	{
		return $this->parent !== null;
	}

	public function detach(): void
	{
		if ($this->parent)
		{
			$this->parent->detachChild($this);
			$this->_phpVersion = $this->parent->getPhpVersion();
			$this->parent = null;
		}
	}

	abstract protected function detachChild(Node $child): void;

	abstract protected function replaceChild(Node $child, Node $replacement): void;

	/** @return Node[] */
	abstract public function getChildNodes(): array;

	/** @return iterable|Node[] */
	public function findNodes(Specification $spec): iterable
	{
		if ($spec->isSatisfiedBy($this))
		{
			yield $this;
		}

		foreach ($this->getChildNodes() as $node)
		{
			yield from $node->findNodes($spec);
		}
	}

	/**
	 * @template W of self
	 * @param Node&WrapperNode $wrapper
	 * @phpstan-param W&WrapperNode<static> $wrapper
	 * @phpstan-return W
	 */
	public function wrapIn(WrapperNode $wrapper): Node
	{
		if ($parent = $this->getParent())
		{
			$parent->replaceChild($this, $wrapper);
		}
		$wrapper->wrapNode($this);
		return $wrapper;
	}

	/** @return iterable|Token[] */
	abstract public function iterTokens(): iterable;

	public function getFirstToken(): ?Token
	{
		foreach ($this->iterTokens() as $t)
		{
			return $t;
		}
		return null;
	}

	public function getLastToken(): ?Token
	{
		$last = null;
		foreach ($this->iterTokens() as $t)
		{
			$last = $t;
		}
		return $last;
	}

	public function getLeftWhitespace(): string
	{
		$token = $this->getFirstToken();
		return $token ? $token->getLeftWhitespace() : "";
	}

	public function getRightWhitespace(): string
	{
		$token = $this->getLastToken();
		return $token ? $token->getRightWhitespace() : "";
	}

	/**
	 * @return static
	 */
	public function clone(): self
	{
		$clone = clone $this;
		$clone->parent = null;
		return $clone;
	}

	public function validate(): void
	{
		/** @var Token|null $previous */
		$previous = null;
		foreach ($this->iterTokens() as $token)
		{
			if (
				$previous
				&& $previous->getRightWhitespace() === ""
				&& $token->getLeftWhitespace() === ""
				&& TokenType::requireSeparatingWhitespace($previous->getType(), $token->getType())
			)
			{
				throw ValidationException::missingWhitespace($token);
			}

			$previous = $token;
		}
	}

	public function autocorrect(): void
	{
		/** @var Token|null $previous */
		$previous = null;
		foreach ($this->iterTokens() as $token)
		{
			if (
				$previous
				&& $previous->getRightWhitespace() === ""
				&& $token->getLeftWhitespace() === ""
				&& TokenType::requireSeparatingWhitespace($previous->getType(), $token->getType())
			)
			{
				$previous->setRightWhitespace(" ");
			}

			$previous = $token;
		}
	}

	public function repr(): string
	{
		$parts = explode("\\", \get_class($this));
		return \end($parts);
	}

	abstract public function toPhp(): string;

	abstract public function debugDump(string $indent = ""): void;

	final public function __toString(): string
	{
		return $this->toPhp();
	}

	/**
	 * Attempts to convert this node to the equivalent PHP-Parser node.
	 *
	 * @return mixed
	 */
	public function convertToPhpParser()
	{
		throw new PhiException('Failed to convert ' . $this->repr() . ' to PHP-Parser node', $this);
	}
}
