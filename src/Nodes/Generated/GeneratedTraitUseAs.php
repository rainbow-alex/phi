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

trait GeneratedTraitUseAs
{
	/**
	 * @var \Phi\Nodes\Oop\TraitMethodRef|null
	 */
	private $method;

	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $modifier;

	/**
	 * @var \Phi\Token|null
	 */
	private $alias;

	/**
	 * @var \Phi\Token|null
	 */
	private $semiColon;

	/**
	 */
	public function __construct()
	{
	}

	/**
	 * @param \Phi\Nodes\Oop\TraitMethodRef $method
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token|null $modifier
	 * @param \Phi\Token|null $alias
	 * @param \Phi\Token $semiColon
	 * @return self
	 */
	public static function __instantiateUnchecked($method, $keyword, $modifier, $alias, $semiColon)
	{
		$instance = new self;
	$instance->setMethod($method);
	$instance->setKeyword($keyword);
	$instance->setModifier($modifier);
	$instance->setAlias($alias);
	$instance->setSemiColon($semiColon);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->method,
			$this->keyword,
			$this->modifier,
			$this->alias,
			$this->semiColon,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->method === $childToDetach)
			return $this->method;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->modifier === $childToDetach)
			return $this->modifier;
		if ($this->alias === $childToDetach)
			return $this->alias;
		if ($this->semiColon === $childToDetach)
			return $this->semiColon;
		throw new \LogicException();
	}

	public function getMethod(): \Phi\Nodes\Oop\TraitMethodRef
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
	 * @param \Phi\Nodes\Oop\TraitMethodRef|\Phi\Node|string|null $method
	 */
	public function setMethod($method): void
	{
		if ($method !== null)
		{
			/** @var \Phi\Nodes\Oop\TraitMethodRef $method */
			$method = NodeCoercer::coerce($method, \Phi\Nodes\Oop\TraitMethodRef::class, $this->getPhpVersion());
			$method->detach();
			$method->parent = $this;
		}
		if ($this->method !== null)
		{
			$this->method->detach();
		}
		$this->method = $method;
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

	public function getModifier(): ?\Phi\Token
	{
		return $this->modifier;
	}

	public function hasModifier(): bool
	{
		return $this->modifier !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $modifier
	 */
	public function setModifier($modifier): void
	{
		if ($modifier !== null)
		{
			/** @var \Phi\Token $modifier */
			$modifier = NodeCoercer::coerce($modifier, \Phi\Token::class, $this->getPhpVersion());
			$modifier->detach();
			$modifier->parent = $this;
		}
		if ($this->modifier !== null)
		{
			$this->modifier->detach();
		}
		$this->modifier = $modifier;
	}

	public function getAlias(): ?\Phi\Token
	{
		return $this->alias;
	}

	public function hasAlias(): bool
	{
		return $this->alias !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $alias
	 */
	public function setAlias($alias): void
	{
		if ($alias !== null)
		{
			/** @var \Phi\Token $alias */
			$alias = NodeCoercer::coerce($alias, \Phi\Token::class, $this->getPhpVersion());
			$alias->detach();
			$alias->parent = $this;
		}
		if ($this->alias !== null)
		{
			$this->alias->detach();
		}
		$this->alias = $alias;
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
		if ($this->method === null)
			throw ValidationException::missingChild($this, "method");
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->semiColon === null)
			throw ValidationException::missingChild($this, "semiColon");
		if ($this->keyword->getType() !== 132)
			throw ValidationException::invalidSyntax($this->keyword, [132]);
		if ($this->modifier)
		if (!\in_array($this->modifier->getType(), [234, 233, 232], true))
			throw ValidationException::invalidSyntax($this->modifier, [234, 233, 232]);
		if ($this->alias)
		if ($this->alias->getType() !== 245)
			throw ValidationException::invalidSyntax($this->alias, [245]);
		if ($this->semiColon->getType() !== 114)
			throw ValidationException::invalidSyntax($this->semiColon, [114]);


		$this->extraValidation($flags);

		$this->method->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->method)
			$this->method->_autocorrect();
		if (!$this->keyword)
			$this->setKeyword(new Token(132, 'as'));
		if (!$this->semiColon)
			$this->setSemiColon(new Token(114, ';'));

		$this->extraAutocorrect();
	}
}
