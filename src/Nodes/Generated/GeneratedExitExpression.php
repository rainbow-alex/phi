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

trait GeneratedExitExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftParenthesis;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $expression;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
	 */
	public function __construct($expression = null)
	{
		if ($expression !== null)
		{
			$this->setExpression($expression);
		}
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token|null $leftParenthesis
	 * @param \Phi\Nodes\Expression|null $expression
	 * @param \Phi\Token|null $rightParenthesis
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $leftParenthesis, $expression, $rightParenthesis)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->setExpression($expression);
	$instance->setRightParenthesis($rightParenthesis);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->leftParenthesis,
			$this->expression,
			$this->rightParenthesis,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->expression === $childToDetach)
			return $this->expression;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
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

	public function getLeftParenthesis(): ?\Phi\Token
	{
		return $this->leftParenthesis;
	}

	public function hasLeftParenthesis(): bool
	{
		return $this->leftParenthesis !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
	 */
	public function setLeftParenthesis($leftParenthesis): void
	{
		if ($leftParenthesis !== null)
		{
			/** @var \Phi\Token $leftParenthesis */
			$leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
			$leftParenthesis->detach();
			$leftParenthesis->parent = $this;
		}
		if ($this->leftParenthesis !== null)
		{
			$this->leftParenthesis->detach();
		}
		$this->leftParenthesis = $leftParenthesis;
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

	public function getRightParenthesis(): ?\Phi\Token
	{
		return $this->rightParenthesis;
	}

	public function hasRightParenthesis(): bool
	{
		return $this->rightParenthesis !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
	 */
	public function setRightParenthesis($rightParenthesis): void
	{
		if ($rightParenthesis !== null)
		{
			/** @var \Phi\Token $rightParenthesis */
			$rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
			$rightParenthesis->detach();
			$rightParenthesis->parent = $this;
		}
		if ($this->rightParenthesis !== null)
		{
			$this->rightParenthesis->detach();
		}
		$this->rightParenthesis = $rightParenthesis;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->keyword->getType() !== 178)
			throw ValidationException::invalidSyntax($this->keyword, [178]);
		if ($this->leftParenthesis)
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		if ($this->rightParenthesis)
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		if ($this->expression)
			$this->expression->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(178, 'exit'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		if ($this->expression)
			$this->expression->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));

		$this->extraAutocorrect();
	}
}
