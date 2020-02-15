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

trait GeneratedSpecialType
{
	/**
	 * @var \Phi\Token|null
	 */
	private $name;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 */
	public function __construct($name = null)
	{
		if ($name !== null)
		{
			$this->setName($name);
		}
	}

	/**
	 * @param \Phi\Token $name
	 * @return self
	 */
	public static function __instantiateUnchecked($name)
	{
		$instance = new self;
	$instance->setName($name);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->name,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->name === $childToDetach)
			return $this->name;
		throw new \LogicException();
	}

	public function getName(): \Phi\Token
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
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 */
	public function setName($name): void
	{
		if ($name !== null)
		{
			/** @var \Phi\Token $name */
			$name = NodeCoercer::coerce($name, \Phi\Token::class, $this->getPhpVersion());
			$name->detach();
			$name->parent = $this;
		}
		if ($this->name !== null)
		{
			$this->name->detach();
		}
		$this->name = $name;
	}

	public function _validate(int $flags): void
	{
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if (!\in_array($this->name->getType(), [130, 137, 244], true))
			throw ValidationException::invalidSyntax($this->name, [130, 137, 244]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{

		$this->extraAutocorrect();
	}
}
