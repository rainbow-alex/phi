<?php

declare(strict_types=1);

/**
 * This code is generated.
 * @see meta/
 */

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Exception\TreeException;
use Phi\NodeCoercer;
use Phi\Exception\ValidationException;

trait GeneratedTraitMethodRef
{
	/**
	 * @var \Phi\Nodes\Helpers\Name|null
	 */
	private $name;

	/**
	 * @var \Phi\Token|null
	 */
	private $doubleColon;

	/**
	 * @var \Phi\Token|null
	 */
	private $method;

	/**
	 */
	public function __construct()
	{
	}

	/**
	 * @param \Phi\Nodes\Helpers\Name|null $name
	 * @param \Phi\Token|null $doubleColon
	 * @param \Phi\Token $method
	 * @return self
	 */
	public static function __instantiateUnchecked($name, $doubleColon, $method)
	{
		$instance = new self;
	$instance->setName($name);
	$instance->setDoubleColon($doubleColon);
	$instance->setMethod($method);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->name,
			$this->doubleColon,
			$this->method,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->doubleColon === $childToDetach)
			return $this->doubleColon;
		if ($this->method === $childToDetach)
			return $this->method;
		throw new \LogicException();
	}

	public function getName(): ?\Phi\Nodes\Helpers\Name
	{
		return $this->name;
	}

	public function hasName(): bool
	{
		return $this->name !== null;
	}

	/**
	 * @param \Phi\Nodes\Helpers\Name|\Phi\Node|string|null $name
	 */
	public function setName($name): void
	{
		if ($name !== null)
		{
			/** @var \Phi\Nodes\Helpers\Name $name */
			$name = NodeCoercer::coerce($name, \Phi\Nodes\Helpers\Name::class, $this->getPhpVersion());
			$name->detach();
			$name->parent = $this;
		}
		if ($this->name !== null)
		{
			$this->name->detach();
		}
		$this->name = $name;
	}

	public function getDoubleColon(): ?\Phi\Token
	{
		return $this->doubleColon;
	}

	public function hasDoubleColon(): bool
	{
		return $this->doubleColon !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $doubleColon
	 */
	public function setDoubleColon($doubleColon): void
	{
		if ($doubleColon !== null)
		{
			/** @var \Phi\Token $doubleColon */
			$doubleColon = NodeCoercer::coerce($doubleColon, \Phi\Token::class, $this->getPhpVersion());
			$doubleColon->detach();
			$doubleColon->parent = $this;
		}
		if ($this->doubleColon !== null)
		{
			$this->doubleColon->detach();
		}
		$this->doubleColon = $doubleColon;
	}

	public function getMethod(): \Phi\Token
	{
		if ($this->method === null)
		{
			throw TreeException::missingNode($this, "method");
		}
		return $this->method;
	}

	public function hasMethod(): bool
	{
		return $this->method !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $method
	 */
	public function setMethod($method): void
	{
		if ($method !== null)
		{
			/** @var \Phi\Token $method */
			$method = NodeCoercer::coerce($method, \Phi\Token::class, $this->getPhpVersion());
			$method->detach();
			$method->parent = $this;
		}
		if ($this->method !== null)
		{
			$this->method->detach();
		}
		$this->method = $method;
	}

	public function _validate(int $flags): void
	{
		if ($this->method === null)
			throw ValidationException::missingChild($this, "method");
		if ($this->doubleColon)
		if ($this->doubleColon->getType() !== 163)
			throw ValidationException::invalidSyntax($this->doubleColon, [163]);
		if ($this->method->getType() !== 245)
			throw ValidationException::invalidSyntax($this->method, [245]);


		$this->extraValidation($flags);

		if ($this->name)
			$this->name->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->name)
			$this->name->_autocorrect();
		if (!$this->doubleColon)
			$this->setDoubleColon(new Token(163, '::'));

		$this->extraAutocorrect();
	}
}
