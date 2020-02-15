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

trait GeneratedClassNameResolutionExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $class;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator;

	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
	 */
	public function __construct($class = null)
	{
		if ($class !== null)
		{
			$this->setClass($class);
		}
	}

	/**
	 * @param \Phi\Nodes\Expression $class
	 * @param \Phi\Token $operator
	 * @param \Phi\Token $keyword
	 * @return self
	 */
	public static function __instantiateUnchecked($class, $operator, $keyword)
	{
		$instance = new self;
	$instance->setClass($class);
	$instance->setOperator($operator);
	$instance->setKeyword($keyword);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->class,
			$this->operator,
			$this->keyword,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->class === $childToDetach)
			return $this->class;
		if ($this->operator === $childToDetach)
			return $this->operator;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		throw new \LogicException();
	}

	public function getClass(): \Phi\Nodes\Expression
	{
		if ($this->class === null)
		{
			throw TreeException::missingNode($this, "class");
		}
		return $this->class;
	}

	public function hasClass(): bool
	{
		return $this->class !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
	 */
	public function setClass($class): void
	{
		if ($class !== null)
		{
			/** @var \Phi\Nodes\Expression $class */
			$class = NodeCoercer::coerce($class, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$class->detach();
			$class->parent = $this;
		}
		if ($this->class !== null)
		{
			$this->class->detach();
		}
		$this->class = $class;
	}

	public function getOperator(): \Phi\Token
	{
		if ($this->operator === null)
		{
			throw TreeException::missingNode($this, "operator");
		}
		return $this->operator;
	}

	public function hasOperator(): bool
	{
		return $this->operator !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $operator
	 */
	public function setOperator($operator): void
	{
		if ($operator !== null)
		{
			/** @var \Phi\Token $operator */
			$operator = NodeCoercer::coerce($operator, \Phi\Token::class, $this->getPhpVersion());
			$operator->detach();
			$operator->parent = $this;
		}
		if ($this->operator !== null)
		{
			$this->operator->detach();
		}
		$this->operator = $operator;
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

	public function _validate(int $flags): void
	{
		if ($this->class === null)
			throw ValidationException::missingChild($this, "class");
		if ($this->operator === null)
			throw ValidationException::missingChild($this, "operator");
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->operator->getType() !== 163)
			throw ValidationException::invalidSyntax($this->operator, [163]);
		if ($this->keyword->getType() !== 140)
			throw ValidationException::invalidSyntax($this->keyword, [140]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->class->_validate(1);
	}

	public function _autocorrect(): void
	{
		if ($this->class)
			$this->class->_autocorrect();
		if (!$this->operator)
			$this->setOperator(new Token(163, '::'));
		if (!$this->keyword)
			$this->setKeyword(new Token(140, 'class'));

		$this->extraAutocorrect();
	}
}
