<?php

declare(strict_types=1);

namespace Phi\Meta;

use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Token;

class NodeDef
{
	/** @var string */
	public $className;
	/** @var NodeChildDef[] */
	public $children = [];
	/** @var string[] */
	public $constructor = [];
	/** @var int */
	public $invalidContexts = 0;
	/** @var bool */
	public $validateChildren = true;

	public function __construct(string $className)
	{
		$this->className = $className;
	}

	/**
	 * @param int|string $validationFlags
	 */
	public function node(string $name, string $class, $validationFlags = 0)
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			false,
			$class,
			null,
			null,
			null,
			$validationFlags
		);
		return $this;
	}

	/**
	 * @param int|string $validationFlags
	 */
	public function optNode(string $name, string $class, $validationFlags = 0)
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			true,
			$class,
			null,
			null,
			null,
			$validationFlags
		);
		return $this;
	}

	public function token(string $name, $tokenTypes)
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			false,
			Token::class,
			null,
			self::ensureArray($tokenTypes),
			null
		);
		return $this;
	}

	public function optToken(string $name, $tokenTypes)
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			true,
			Token::class,
			null,
			self::ensureArray($tokenTypes),
			null
		);
		return $this;
	}

	public function nodeList(string $name, string $itemClass): self
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			false,
			NodesList::class,
			$itemClass,
			null,
			null
		);
		return $this;
	}

	public function withTokenList(string $name, array $tokenTypes): self
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			false,
			NodesList::class,
			Token::class,
			self::ensureArray($tokenTypes),
			null
		);
		return $this;
	}

	/**
	 * @param int|string $validationFlags
	 */
	public function sepNodeList(string $name, string $itemClass, $separator, $validationFlags = 0): self
	{
		assert(!isset($this->children[$name]));
		$this->children[$name] = new NodeChildDef(
			$name,
			false,
			SeparatedNodesList::class,
			$itemClass,
			null,
			self::ensureArray($separator),
			$validationFlags
		);
		return $this;
	}

	public function constructor(string ...$params): self
	{
		assert(!$this->constructor);
		foreach ($params as $p)
		{
			foreach ($this->children as $name => $c)
			{
				if ($name === $p || $c->singularName() === $p)
				{
					continue 2;
				}
			}

			assert(false);
		}

		$this->constructor = $params;
		return $this;
	}

	public function invalidContexts(int $ctx): self
	{
		$this->invalidContexts = $ctx;
		return $this;
	}

	public function validateChildren(bool $v): self
	{
		$this->validateChildren = $v;
		return $this;
	}

	public function shortClassName(): string
	{
		$parts = \explode("\\", $this->className);
		return \end($parts);
	}

	public function shortGeneratedClassName(): string
	{
		return "Generated" . \rtrim($this->shortClassName(), "_");
	}

	private static function ensureArray($v)
	{
		return is_array($v) ? $v : [$v];
	}
}
