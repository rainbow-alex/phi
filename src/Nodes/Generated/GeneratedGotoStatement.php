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
	private $delimiter;

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
	 * @param \Phi\Token $delimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $label, $delimiter)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLabel($label);
	$instance->setDelimiter($delimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->label,
			$this->delimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->label === $childToDetach)
			return $this->label;
		if ($this->delimiter === $childToDetach)
			return $this->delimiter;
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

	public function getDelimiter(): \Phi\Token
	{
		if ($this->delimiter === null)
		{
			throw TreeException::missingNode($this, "delimiter");
		}
		return $this->delimiter;
	}

	public function hasDelimiter(): bool
	{
		return $this->delimiter !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $delimiter
	 */
	public function setDelimiter($delimiter): void
	{
		if ($delimiter !== null)
		{
			/** @var \Phi\Token $delimiter */
			$delimiter = NodeCoercer::coerce($delimiter, \Phi\Token::class, $this->getPhpVersion());
			$delimiter->detach();
			$delimiter->parent = $this;
		}
		if ($this->delimiter !== null)
		{
			$this->delimiter->detach();
		}
		$this->delimiter = $delimiter;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->label === null)
			throw ValidationException::missingChild($this, "label");
		if ($this->delimiter === null)
			throw ValidationException::missingChild($this, "delimiter");
		if ($this->keyword->getType() !== 189)
			throw ValidationException::invalidSyntax($this->keyword, [189]);
		if ($this->label->getType() !== 245)
			throw ValidationException::invalidSyntax($this->label, [245]);
		if (!\in_array($this->delimiter->getType(), [114, 143], true))
			throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(189, 'goto'));

		$this->extraAutocorrect();
	}
}
