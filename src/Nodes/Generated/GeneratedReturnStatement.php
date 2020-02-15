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

trait GeneratedReturnStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $expression;

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
	 * @param \Phi\Nodes\Expression|null $expression
	 * @param \Phi\Token $delimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $expression, $delimiter)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setExpression($expression);
	$instance->setDelimiter($delimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->expression,
			$this->delimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->expression === $childToDetach)
			return $this->expression;
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

	public function getExpression(): ?\Phi\Nodes\Expression
	{
		return $this->expression;
	}

	public function hasExpression(): bool
	{
		return $this->expression !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
	 */
	public function setExpression($expression): void
	{
		if ($expression !== null)
		{
			/** @var \Phi\Nodes\Expression $expression */
			$expression = NodeCoercer::coerce($expression, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$expression->detach();
			$expression->parent = $this;
		}
		if ($this->expression !== null)
		{
			$this->expression->detach();
		}
		$this->expression = $expression;
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
		if ($this->keyword->getType() !== 237)
			throw ValidationException::invalidSyntax($this->keyword, [237]);
		if (!\in_array($this->delimiter->getType(), [114, 143], true))
			throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


		$this->extraValidation($flags);

		if ($this->expression)
			$this->expression->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(237, 'return'));
		if ($this->expression)
			$this->expression->_autocorrect();

		$this->extraAutocorrect();
	}
}
