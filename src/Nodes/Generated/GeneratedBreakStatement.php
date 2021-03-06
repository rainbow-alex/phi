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

trait GeneratedBreakStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Expressions\NumberLiteral|null
	 */
	private $levels;

	/**
	 * @var \Phi\Token|null
	 */
	private $delimiter;

	/**
	 */
	public function __construct()
	{
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Nodes\Expressions\NumberLiteral|null $levels
	 * @param \Phi\Token $delimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $levels, $delimiter)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLevels($levels);
	$instance->setDelimiter($delimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->levels,
			$this->delimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->levels === $childToDetach)
			return $this->levels;
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

	public function getLevels(): ?\Phi\Nodes\Expressions\NumberLiteral
	{
		return $this->levels;
	}

	public function hasLevels(): bool
	{
		return $this->levels !== null;
	}

	/**
	 * @param \Phi\Nodes\Expressions\NumberLiteral|\Phi\Node|string|null $levels
	 */
	public function setLevels($levels): void
	{
		if ($levels !== null)
		{
			/** @var \Phi\Nodes\Expressions\NumberLiteral $levels */
			$levels = NodeCoercer::coerce($levels, \Phi\Nodes\Expressions\NumberLiteral::class, $this->getPhpVersion());
			$levels->detach();
			$levels->parent = $this;
		}
		if ($this->levels !== null)
		{
			$this->levels->detach();
		}
		$this->levels = $levels;
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
		if ($this->delimiter === null)
			throw ValidationException::missingChild($this, "delimiter");
		if ($this->keyword->getType() !== 136)
			throw ValidationException::invalidSyntax($this->keyword, [136]);
		if (!\in_array($this->delimiter->getType(), [114, 143], true))
			throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


		$this->extraValidation($flags);

		if ($this->levels)
			$this->levels->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(136, 'break'));
		if ($this->levels)
			$this->levels->_autocorrect();

		$this->extraAutocorrect();
	}
}
