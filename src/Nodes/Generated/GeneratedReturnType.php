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

trait GeneratedReturnType
{
	/**
	 * @var \Phi\Token|null
	 */
	private $colon;

	/**
	 * @var \Phi\Nodes\Type|null
	 */
	private $type;

	/**
	 * @param \Phi\Nodes\Type|\Phi\Node|string|null $type
	 */
	public function __construct($type = null)
	{
		if ($type !== null)
		{
			$this->setType($type);
		}
	}

	/**
	 * @param \Phi\Token $colon
	 * @param \Phi\Nodes\Type $type
	 * @return self
	 */
	public static function __instantiateUnchecked($colon, $type)
	{
		$instance = new self;
	$instance->setColon($colon);
	$instance->setType($type);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->colon,
			$this->type,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->colon === $childToDetach)
			return $this->colon;
		if ($this->type === $childToDetach)
			return $this->type;
		throw new \LogicException();
	}

	public function getColon(): \Phi\Token
	{
		if ($this->colon === null)
		{
			throw TreeException::missingNode($this, "colon");
		}
		return $this->colon;
	}

	public function hasColon(): bool
	{
		return $this->colon !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $colon
	 */
	public function setColon($colon): void
	{
		if ($colon !== null)
		{
			/** @var \Phi\Token $colon */
			$colon = NodeCoercer::coerce($colon, \Phi\Token::class, $this->getPhpVersion());
			$colon->detach();
			$colon->parent = $this;
		}
		if ($this->colon !== null)
		{
			$this->colon->detach();
		}
		$this->colon = $colon;
	}

	public function getType(): \Phi\Nodes\Type
	{
		if ($this->type === null)
		{
			throw TreeException::missingNode($this, "type");
		}
		return $this->type;
	}

	public function hasType(): bool
	{
		return $this->type !== null;
	}

	/**
	 * @param \Phi\Nodes\Type|\Phi\Node|string|null $type
	 */
	public function setType($type): void
	{
		if ($type !== null)
		{
			/** @var \Phi\Nodes\Type $type */
			$type = NodeCoercer::coerce($type, \Phi\Nodes\Type::class, $this->getPhpVersion());
			$type->detach();
			$type->parent = $this;
		}
		if ($this->type !== null)
		{
			$this->type->detach();
		}
		$this->type = $type;
	}

	public function _validate(int $flags): void
	{
		if ($this->colon === null)
			throw ValidationException::missingChild($this, "colon");
		if ($this->type === null)
			throw ValidationException::missingChild($this, "type");
		if ($this->colon->getType() !== 113)
			throw ValidationException::invalidSyntax($this->colon, [113]);


		$this->extraValidation($flags);

		$this->type->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->colon)
			$this->setColon(new Token(113, ':'));
		if ($this->type)
			$this->type->_autocorrect();

		$this->extraAutocorrect();
	}
}
