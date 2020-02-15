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

trait GeneratedIsNotEqualExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $left;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $right;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $left
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $right
	 */
	public function __construct($left = null, $right = null)
	{
		if ($left !== null)
		{
			$this->setLeft($left);
		}
		if ($right !== null)
		{
			$this->setRight($right);
		}
	}

	/**
	 * @param \Phi\Nodes\Expression $left
	 * @param \Phi\Token $operator
	 * @param \Phi\Nodes\Expression $right
	 * @return self
	 */
	public static function __instantiateUnchecked($left, $operator, $right)
	{
		$instance = new self;
	$instance->setLeft($left);
	$instance->setOperator($operator);
	$instance->setRight($right);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->left,
			$this->operator,
			$this->right,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->left === $childToDetach)
			return $this->left;
		if ($this->operator === $childToDetach)
			return $this->operator;
		if ($this->right === $childToDetach)
			return $this->right;
		throw new \LogicException();
	}

	public function getLeft(): \Phi\Nodes\Expression
	{
		if ($this->left === null)
		{
			throw TreeException::missingNode($this, "left");
		}
		return $this->left;
	}

	public function hasLeft(): bool
	{
		return $this->left !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $left
	 */
	public function setLeft($left): void
	{
		if ($left !== null)
		{
			/** @var \Phi\Nodes\Expression $left */
			$left = NodeCoercer::coerce($left, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$left->detach();
			$left->parent = $this;
		}
		if ($this->left !== null)
		{
			$this->left->detach();
		}
		$this->left = $left;
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

	public function getRight(): \Phi\Nodes\Expression
	{
		if ($this->right === null)
		{
			throw TreeException::missingNode($this, "right");
		}
		return $this->right;
	}

	public function hasRight(): bool
	{
		return $this->right !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $right
	 */
	public function setRight($right): void
	{
		if ($right !== null)
		{
			/** @var \Phi\Nodes\Expression $right */
			$right = NodeCoercer::coerce($right, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$right->detach();
			$right->parent = $this;
		}
		if ($this->right !== null)
		{
			$this->right->detach();
		}
		$this->right = $right;
	}

	public function _validate(int $flags): void
	{
		if ($this->left === null)
			throw ValidationException::missingChild($this, "left");
		if ($this->operator === null)
			throw ValidationException::missingChild($this, "operator");
		if ($this->right === null)
			throw ValidationException::missingChild($this, "right");
		if ($this->operator->getType() !== 205)
			throw ValidationException::invalidSyntax($this->operator, [205]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->left->_validate(1);
		$this->right->_validate(1);
	}

	public function _autocorrect(): void
	{
		if ($this->left)
			$this->left->_autocorrect();
		if (!$this->operator)
			$this->setOperator(new Token(205, '!='));
		if ($this->right)
			$this->right->_autocorrect();

		$this->extraAutocorrect();
	}
}
