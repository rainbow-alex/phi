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

trait GeneratedGotoStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $label;

	/**
	 * @var \Phi\Token|null
	 */
	private $semiColon;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $label
	 */
	public function __construct($label = null)
	{
		if ($label !== null)
		{
			$this->setLabel($label);
		}
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $label
	 * @param \Phi\Token|null $semiColon
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $label, $semiColon)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLabel($label);
	$instance->setSemiColon($semiColon);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->label,
			$this->semiColon,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->label === $childToDetach)
			return $this->label;
		if ($this->semiColon === $childToDetach)
			return $this->semiColon;
		throw new \LogicException();
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

	public function getLabel(): \Phi\Token
	{
		if ($this->label === null)
		{
			throw TreeException::missingNode($this, "label");
		}
		return $this->label;
	}

	public function hasLabel(): bool
	{
		return $this->label !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $label
	 */
	public function setLabel($label): void
	{
		if ($label !== null)
		{
			/** @var \Phi\Token $label */
			$label = NodeCoercer::coerce($label, \Phi\Token::class, $this->getPhpVersion());
			$label->detach();
			$label->parent = $this;
		}
		if ($this->label !== null)
		{
			$this->label->detach();
		}
		$this->label = $label;
	}

	public function getSemiColon(): ?\Phi\Token
	{
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
		if ($this->label === null)
			throw ValidationException::missingChild($this, "label");
		if ($this->keyword->getType() !== 189)
			throw ValidationException::invalidSyntax($this->keyword, [189]);
		if ($this->label->getType() !== 245)
			throw ValidationException::invalidSyntax($this->label, [245]);
		if ($this->semiColon)
		if ($this->semiColon->getType() !== 114)
			throw ValidationException::invalidSyntax($this->semiColon, [114]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(189, 'goto'));
		if (!$this->semiColon)
			$this->setSemiColon(new Token(114, ';'));

		$this->extraAutocorrect();
	}
}
