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

trait GeneratedProperty
{
	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	private $modifiers;

	/**
	 * @var \Phi\Token|null
	 */
	private $name;

	/**
	 * @var \Phi\Nodes\Helpers\Default_|null
	 */
	private $default;

	/**
	 * @var \Phi\Token|null
	 */
	private $semiColon;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 */
	public function __construct($name = null)
	{
		$this->modifiers = new \Phi\Nodes\Base\NodesList(\Phi\Token::class);
		if ($name !== null)
		{
			$this->setName($name);
		}
	}

	/**
	 * @param mixed[] $modifiers
	 * @param \Phi\Token $name
	 * @param \Phi\Nodes\Helpers\Default_|null $default
	 * @param \Phi\Token $semiColon
	 * @return self
	 */
	public static function __instantiateUnchecked($modifiers, $name, $default, $semiColon)
	{
		$instance = new self;
	$instance->modifiers->__initUnchecked($modifiers);
	$instance->modifiers->parent = $instance;
	$instance->setName($name);
	$instance->setDefault($default);
	$instance->setSemiColon($semiColon);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->modifiers,
			$this->name,
			$this->default,
			$this->semiColon,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->modifiers === $childToDetach)
			return $this->modifiers;
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->default === $childToDetach)
			return $this->default;
		if ($this->semiColon === $childToDetach)
			return $this->semiColon;
		throw new \LogicException();
	}

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	public function getModifiers(): \Phi\Nodes\Base\NodesList
	{
		return $this->modifiers;
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

	public function getDefault(): ?\Phi\Nodes\Helpers\Default_
	{
		return $this->default;
	}

	public function hasDefault(): bool
	{
		return $this->default !== null;
	}

	/**
	 * @param \Phi\Nodes\Helpers\Default_|\Phi\Node|string|null $default
	 */
	public function setDefault($default): void
	{
		if ($default !== null)
		{
			/** @var \Phi\Nodes\Helpers\Default_ $default */
			$default = NodeCoercer::coerce($default, \Phi\Nodes\Helpers\Default_::class, $this->getPhpVersion());
			$default->detach();
			$default->parent = $this;
		}
		if ($this->default !== null)
		{
			$this->default->detach();
		}
		$this->default = $default;
	}

	public function getSemiColon(): \Phi\Token
	{
		if ($this->semiColon === null)
		{
			throw TreeException::missingNode($this, "semiColon");
		}
		return $this->semiColon;
	}

	public function hasSemiColon(): bool
	{
		return $this->semiColon !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $semiColon
	 */
	public function setSemiColon($semiColon): void
	{
		if ($semiColon !== null)
		{
			/** @var \Phi\Token $semiColon */
			$semiColon = NodeCoercer::coerce($semiColon, \Phi\Token::class, $this->getPhpVersion());
			$semiColon->detach();
			$semiColon->parent = $this;
		}
		if ($this->semiColon !== null)
		{
			$this->semiColon->detach();
		}
		$this->semiColon = $semiColon;
	}

	public function _validate(int $flags): void
	{
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if ($this->semiColon === null)
			throw ValidationException::missingChild($this, "semiColon");
		foreach ($this->modifiers as $t)
			if (!\in_array($t->getType(), [234, 233, 232, 244], true))
				throw ValidationException::invalidSyntax($t, [234, 233, 232, 244]);
		if ($this->name->getType() !== 257)
			throw ValidationException::invalidSyntax($this->name, [257]);
		if ($this->semiColon->getType() !== 114)
			throw ValidationException::invalidSyntax($this->semiColon, [114]);


		$this->extraValidation($flags);

		if ($this->default)
			$this->default->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->default)
			$this->default->_autocorrect();
		if (!$this->semiColon)
			$this->setSemiColon(new Token(114, ';'));

		$this->extraAutocorrect();
	}
}
