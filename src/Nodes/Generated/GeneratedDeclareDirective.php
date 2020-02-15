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

trait GeneratedDeclareDirective
{
	/**
	 * @var \Phi\Token|null
	 */
	private $key;

	/**
	 * @var \Phi\Token|null
	 */
	private $equals;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $value;

	/**
	 */
	public function __construct()
	{
	}

	/**
	 * @param \Phi\Token $key
	 * @param \Phi\Token $equals
	 * @param \Phi\Nodes\Expression $value
	 * @return self
	 */
	public static function __instantiateUnchecked($key, $equals, $value)
	{
		$instance = new self;
	$instance->setKey($key);
	$instance->setEquals($equals);
	$instance->setValue($value);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->key,
			$this->equals,
			$this->value,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->key === $childToDetach)
			return $this->key;
		if ($this->equals === $childToDetach)
			return $this->equals;
		if ($this->value === $childToDetach)
			return $this->value;
		throw new \LogicException();
	}

	public function getKey(): \Phi\Token
	{
		if ($this->key === null)
		{
			throw TreeException::missingNode($this, "key");
		}
		return $this->key;
	}

	public function hasKey(): bool
	{
		return $this->key !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $key
	 */
	public function setKey($key): void
	{
		if ($key !== null)
		{
			/** @var \Phi\Token $key */
			$key = NodeCoercer::coerce($key, \Phi\Token::class, $this->getPhpVersion());
			$key->detach();
			$key->parent = $this;
		}
		if ($this->key !== null)
		{
			$this->key->detach();
		}
		$this->key = $key;
	}

	public function getEquals(): \Phi\Token
	{
		if ($this->equals === null)
		{
			throw TreeException::missingNode($this, "equals");
		}
		return $this->equals;
	}

	public function hasEquals(): bool
	{
		return $this->equals !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $equals
	 */
	public function setEquals($equals): void
	{
		if ($equals !== null)
		{
			/** @var \Phi\Token $equals */
			$equals = NodeCoercer::coerce($equals, \Phi\Token::class, $this->getPhpVersion());
			$equals->detach();
			$equals->parent = $this;
		}
		if ($this->equals !== null)
		{
			$this->equals->detach();
		}
		$this->equals = $equals;
	}

	public function getValue(): \Phi\Nodes\Expression
	{
		if ($this->value === null)
		{
			throw TreeException::missingNode($this, "value");
		}
		return $this->value;
	}

	public function hasValue(): bool
	{
		return $this->value !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
	 */
	public function setValue($value): void
	{
		if ($value !== null)
		{
			/** @var \Phi\Nodes\Expression $value */
			$value = NodeCoercer::coerce($value, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$value->detach();
			$value->parent = $this;
		}
		if ($this->value !== null)
		{
			$this->value->detach();
		}
		$this->value = $value;
	}

	public function _validate(int $flags): void
	{
		if ($this->key === null)
			throw ValidationException::missingChild($this, "key");
		if ($this->equals === null)
			throw ValidationException::missingChild($this, "equals");
		if ($this->value === null)
			throw ValidationException::missingChild($this, "value");
		if ($this->key->getType() !== 245)
			throw ValidationException::invalidSyntax($this->key, [245]);
		if ($this->equals->getType() !== 116)
			throw ValidationException::invalidSyntax($this->equals, [116]);


		$this->extraValidation($flags);

		$this->value->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->equals)
			$this->setEquals(new Token(116, '='));
		if ($this->value)
			$this->value->_autocorrect();

		$this->extraAutocorrect();
	}
}
