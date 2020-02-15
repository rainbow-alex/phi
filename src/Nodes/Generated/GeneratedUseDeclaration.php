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

trait GeneratedUseDeclaration
{
	/**
	 * @var \Phi\Nodes\Helpers\Name|null
	 */
	private $name;

	/**
	 * @var \Phi\Nodes\Statements\UseAlias|null
	 */
	private $alias;

	/**
	 */
	public function __construct()
	{
	}

	/**
	 * @param \Phi\Nodes\Helpers\Name $name
	 * @param \Phi\Nodes\Statements\UseAlias|null $alias
	 * @return self
	 */
	public static function __instantiateUnchecked($name, $alias)
	{
		$instance = new self;
	$instance->setName($name);
	$instance->setAlias($alias);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->name,
			$this->alias,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->alias === $childToDetach)
			return $this->alias;
		throw new \LogicException();
	}

	public function getName(): \Phi\Nodes\Helpers\Name
	{
		if ($this->name === null)
		{
			throw TreeException::missingNode($this, "name");
		}
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

	public function getAlias(): ?\Phi\Nodes\Statements\UseAlias
	{
		return $this->alias;
	}

	public function hasAlias(): bool
	{
		return $this->alias !== null;
	}

	/**
	 * @param \Phi\Nodes\Statements\UseAlias|\Phi\Node|string|null $alias
	 */
	public function setAlias($alias): void
	{
		if ($alias !== null)
		{
			/** @var \Phi\Nodes\Statements\UseAlias $alias */
			$alias = NodeCoercer::coerce($alias, \Phi\Nodes\Statements\UseAlias::class, $this->getPhpVersion());
			$alias->detach();
			$alias->parent = $this;
		}
		if ($this->alias !== null)
		{
			$this->alias->detach();
		}
		$this->alias = $alias;
	}

	public function _validate(int $flags): void
	{
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");


		$this->extraValidation($flags);

		$this->name->_validate(0);
		if ($this->alias)
			$this->alias->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->name)
			$this->name->_autocorrect();
		if ($this->alias)
			$this->alias->_autocorrect();

		$this->extraAutocorrect();
	}
}
