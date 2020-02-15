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

trait GeneratedClassConstant
{
	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	private $modifiers;

	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $name;

	/**
	 * @var \Phi\Token|null
	 */
	private $equals;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $value;

	/**
	 * @var \Phi\Token|null
	 */
	private $semiColon;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
	 */
	public function __construct($name = null, $value = null)
	{
		$this->modifiers = new \Phi\Nodes\Base\NodesList(\Phi\Token::class);
		if ($name !== null)
		{
			$this->setName($name);
		}
		if ($value !== null)
		{
			$this->setValue($value);
		}
	}

	/**
	 * @param mixed[] $modifiers
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $name
	 * @param \Phi\Token $equals
	 * @param \Phi\Nodes\Expression $value
	 * @param \Phi\Token $semiColon
	 * @return self
	 */
	public static function __instantiateUnchecked($modifiers, $keyword, $name, $equals, $value, $semiColon)
	{
		$instance = new self;
	$instance->modifiers->__initUnchecked($modifiers);
	$instance->modifiers->parent = $instance;
	$instance->setKeyword($keyword);
	$instance->setName($name);
	$instance->setEquals($equals);
	$instance->setValue($value);
	$instance->setSemiColon($semiColon);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->modifiers,
			$this->keyword,
			$this->name,
			$this->equals,
			$this->value,
			$this->semiColon,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->modifiers === $childToDetach)
			return $this->modifiers;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->equals === $childToDetach)
			return $this->equals;
		if ($this->value === $childToDetach)
			return $this->value;
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

	public function getKeyword(): \Phi\Token
	{
		if ($this->keyword === null)
		{
			throw TreeException::missingNode($this, "keyword");
		}
		return $this->keyword;
	}

	public function hasKeyword(): bool
	{
		return $this->keyword !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $keyword
	 */
	public function setKeyword($keyword): void
	{
		if ($keyword !== null)
		{
			/** @var \Phi\Token $keyword */
			$keyword = NodeCoercer::coerce($keyword, \Phi\Token::class, $this->getPhpVersion());
			$keyword->detach();
			$keyword->parent = $this;
		}
		if ($this->keyword !== null)
		{
			$this->keyword->detach();
		}
		$this->keyword = $keyword;
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
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if ($this->equals === null)
			throw ValidationException::missingChild($this, "equals");
		if ($this->value === null)
			throw ValidationException::missingChild($this, "value");
		if ($this->semiColon === null)
			throw ValidationException::missingChild($this, "semiColon");
		foreach ($this->modifiers as $t)
			if (!\in_array($t->getType(), [234, 233, 232], true))
				throw ValidationException::invalidSyntax($t, [234, 233, 232]);
		if ($this->keyword->getType() !== 148)
			throw ValidationException::invalidSyntax($this->keyword, [148]);
		if ($this->name->getType() !== 245)
			throw ValidationException::invalidSyntax($this->name, [245]);
		if ($this->equals->getType() !== 116)
			throw ValidationException::invalidSyntax($this->equals, [116]);
		if ($this->semiColon->getType() !== 114)
			throw ValidationException::invalidSyntax($this->semiColon, [114]);


		$this->extraValidation($flags);

		$this->value->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(148, 'const'));
		if (!$this->equals)
			$this->setEquals(new Token(116, '='));
		if ($this->value)
			$this->value->_autocorrect();
		if (!$this->semiColon)
			$this->setSemiColon(new Token(114, ';'));

		$this->extraAutocorrect();
	}
}
