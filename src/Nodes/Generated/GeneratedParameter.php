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

trait GeneratedParameter
{
	/**
	 * @var \Phi\Nodes\Type|null
	 */
	private $type;

	/**
	 * @var \Phi\Token|null
	 */
	private $byReference;

	/**
	 * @var \Phi\Token|null
	 */
	private $unpack;

	/**
	 * @var \Phi\Token|null
	 */
	private $variable;

	/**
	 * @var \Phi\Nodes\Helpers\Default_|null
	 */
	private $default;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $variable
	 */
	public function __construct($variable = null)
	{
		if ($variable !== null)
		{
			$this->setVariable($variable);
		}
	}

	/**
	 * @param \Phi\Nodes\Type|null $type
	 * @param \Phi\Token|null $byReference
	 * @param \Phi\Token|null $unpack
	 * @param \Phi\Token $variable
	 * @param \Phi\Nodes\Helpers\Default_|null $default
	 * @return self
	 */
	public static function __instantiateUnchecked($type, $byReference, $unpack, $variable, $default)
	{
		$instance = new self;
	$instance->setType($type);
	$instance->setByReference($byReference);
	$instance->setUnpack($unpack);
	$instance->setVariable($variable);
	$instance->setDefault($default);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->type,
			$this->byReference,
			$this->unpack,
			$this->variable,
			$this->default,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->type === $childToDetach)
			return $this->type;
		if ($this->byReference === $childToDetach)
			return $this->byReference;
		if ($this->unpack === $childToDetach)
			return $this->unpack;
		if ($this->variable === $childToDetach)
			return $this->variable;
		if ($this->default === $childToDetach)
			return $this->default;
		throw new \LogicException();
	}

	public function getType(): ?\Phi\Nodes\Type
	{
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

	public function getByReference(): ?\Phi\Token
	{
		return $this->byReference;
	}

	public function hasByReference(): bool
	{
		return $this->byReference !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $byReference
	 */
	public function setByReference($byReference): void
	{
		if ($byReference !== null)
		{
			/** @var \Phi\Token $byReference */
			$byReference = NodeCoercer::coerce($byReference, \Phi\Token::class, $this->getPhpVersion());
			$byReference->detach();
			$byReference->parent = $this;
		}
		if ($this->byReference !== null)
		{
			$this->byReference->detach();
		}
		$this->byReference = $byReference;
	}

	public function getUnpack(): ?\Phi\Token
	{
		return $this->unpack;
	}

	public function hasUnpack(): bool
	{
		return $this->unpack !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $unpack
	 */
	public function setUnpack($unpack): void
	{
		if ($unpack !== null)
		{
			/** @var \Phi\Token $unpack */
			$unpack = NodeCoercer::coerce($unpack, \Phi\Token::class, $this->getPhpVersion());
			$unpack->detach();
			$unpack->parent = $this;
		}
		if ($this->unpack !== null)
		{
			$this->unpack->detach();
		}
		$this->unpack = $unpack;
	}

	public function getVariable(): \Phi\Token
	{
		if ($this->variable === null)
		{
			throw TreeException::missingNode($this, "variable");
		}
		return $this->variable;
	}

	public function hasVariable(): bool
	{
		return $this->variable !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $variable
	 */
	public function setVariable($variable): void
	{
		if ($variable !== null)
		{
			/** @var \Phi\Token $variable */
			$variable = NodeCoercer::coerce($variable, \Phi\Token::class, $this->getPhpVersion());
			$variable->detach();
			$variable->parent = $this;
		}
		if ($this->variable !== null)
		{
			$this->variable->detach();
		}
		$this->variable = $variable;
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

	public function _validate(int $flags): void
	{
		if ($this->variable === null)
			throw ValidationException::missingChild($this, "variable");
		if ($this->byReference)
		if ($this->byReference->getType() !== 104)
			throw ValidationException::invalidSyntax($this->byReference, [104]);
		if ($this->unpack)
		if ($this->unpack->getType() !== 165)
			throw ValidationException::invalidSyntax($this->unpack, [165]);
		if ($this->variable->getType() !== 257)
			throw ValidationException::invalidSyntax($this->variable, [257]);


		$this->extraValidation($flags);

		if ($this->type)
			$this->type->_validate(0);
		if ($this->default)
			$this->default->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->type)
			$this->type->_autocorrect();
		if (!$this->byReference)
			$this->setByReference(new Token(104, '&'));
		if (!$this->unpack)
			$this->setUnpack(new Token(165, '...'));
		if ($this->default)
			$this->default->_autocorrect();

		$this->extraAutocorrect();
	}
}
