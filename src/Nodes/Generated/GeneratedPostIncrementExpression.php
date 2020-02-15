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

trait GeneratedPostIncrementExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $expression;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator;

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
	 * @param \Phi\Nodes\Expression $expression
	 * @param \Phi\Token $operator
	 * @return self
	 */
	public static function __instantiateUnchecked($expression, $operator)
	{
		$instance = new self;
	$instance->setExpression($expression);
	$instance->setOperator($operator);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->expression,
			$this->operator,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->expression === $childToDetach)
			return $this->expression;
		if ($this->operator === $childToDetach)
			return $this->operator;
		throw new \LogicException();
	}

	public function getExpression(): \Phi\Nodes\Expression
	{
		if ($this->expression === null)
		{
			throw TreeException::missingNode($this, "expression");
		}
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

	public function _validate(int $flags): void
	{
		if ($this->expression === null)
			throw ValidationException::missingChild($this, "expression");
		if ($this->operator === null)
			throw ValidationException::missingChild($this, "operator");
		if ($this->operator->getType() !== 193)
			throw ValidationException::invalidSyntax($this->operator, [193]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->expression->_validate(19);
	}

	public function _autocorrect(): void
	{
		if ($this->expression)
			$this->expression->_autocorrect();
		if (!$this->operator)
			$this->setOperator(new Token(193, '++'));

		$this->extraAutocorrect();
	}
}
