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

trait GeneratedTraitUseInsteadof
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
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
	 */
	private $excluded;

	/**
	 * @var \Phi\Token|null
	 */
	private $semiColon;

	/**
	 */
	public function __construct()
	{
		$this->excluded = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Name::class);
	}

	/**
	 * @param \Phi\Nodes\Oop\TraitMethodRef $method
	 * @param \Phi\Token $keyword
	 * @param mixed[] $excluded
	 * @param \Phi\Token $semiColon
	 * @return self
	 */
	public static function __instantiateUnchecked($method, $keyword, $excluded, $semiColon)
	{
		$instance = new self;
	$instance->setMethod($method);
	$instance->setKeyword($keyword);
	$instance->excluded->__initUnchecked($excluded);
	$instance->excluded->parent = $instance;
	$instance->setSemiColon($semiColon);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->method,
			$this->keyword,
			$this->excluded,
			$this->semiColon,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->method === $childToDetach)
			return $this->method;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->excluded === $childToDetach)
			return $this->excluded;
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
	 */
	public function getExcluded(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->excluded;
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
		if ($this->keyword->getType() !== 198)
			throw ValidationException::invalidSyntax($this->keyword, [198]);
		foreach ($this->excluded->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->semiColon->getType() !== 114)
			throw ValidationException::invalidSyntax($this->semiColon, [114]);


		$this->extraValidation($flags);

		$this->method->_validate(0);
		foreach ($this->excluded as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->method)
			$this->method->_autocorrect();
		if (!$this->keyword)
			$this->setKeyword(new Token(198, 'insteadof'));
		foreach ($this->excluded as $t)
			$t->_autocorrect();
		if (!$this->semiColon)
			$this->setSemiColon(new Token(114, ';'));

		$this->extraAutocorrect();
	}
}
