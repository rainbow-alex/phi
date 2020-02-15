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

trait GeneratedTernaryExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $condition;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator1;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $if;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator2;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $else;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $condition
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $if
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $else
	 */
	public function __construct($condition = null, $if = null, $else = null)
	{
		if ($condition !== null)
		{
			$this->setCondition($condition);
		}
		if ($if !== null)
		{
			$this->setIf($if);
		}
		if ($else !== null)
		{
			$this->setElse($else);
		}
	}

	/**
	 * @param \Phi\Nodes\Expression $condition
	 * @param \Phi\Token $operator1
	 * @param \Phi\Nodes\Expression|null $if
	 * @param \Phi\Token $operator2
	 * @param \Phi\Nodes\Expression $else
	 * @return self
	 */
	public static function __instantiateUnchecked($condition, $operator1, $if, $operator2, $else)
	{
		$instance = new self;
	$instance->setCondition($condition);
	$instance->setOperator1($operator1);
	$instance->setIf($if);
	$instance->setOperator2($operator2);
	$instance->setElse($else);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->condition,
			$this->operator1,
			$this->if,
			$this->operator2,
			$this->else,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->condition === $childToDetach)
			return $this->condition;
		if ($this->operator1 === $childToDetach)
			return $this->operator1;
		if ($this->if === $childToDetach)
			return $this->if;
		if ($this->operator2 === $childToDetach)
			return $this->operator2;
		if ($this->else === $childToDetach)
			return $this->else;
		throw new \LogicException();
	}

	public function getCondition(): \Phi\Nodes\Expression
	{
		if ($this->condition === null)
		{
			throw TreeException::missingNode($this, "condition");
		}
		return $this->condition;
	}

	public function hasCondition(): bool
	{
		return $this->condition !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $condition
	 */
	public function setCondition($condition): void
	{
		if ($condition !== null)
		{
			/** @var \Phi\Nodes\Expression $condition */
			$condition = NodeCoercer::coerce($condition, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$condition->detach();
			$condition->parent = $this;
		}
		if ($this->condition !== null)
		{
			$this->condition->detach();
		}
		$this->condition = $condition;
	}

	public function getOperator1(): \Phi\Token
	{
		if ($this->operator1 === null)
		{
			throw TreeException::missingNode($this, "operator1");
		}
		return $this->operator1;
	}

	public function hasOperator1(): bool
	{
		return $this->operator1 !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $operator1
	 */
	public function setOperator1($operator1): void
	{
		if ($operator1 !== null)
		{
			/** @var \Phi\Token $operator1 */
			$operator1 = NodeCoercer::coerce($operator1, \Phi\Token::class, $this->getPhpVersion());
			$operator1->detach();
			$operator1->parent = $this;
		}
		if ($this->operator1 !== null)
		{
			$this->operator1->detach();
		}
		$this->operator1 = $operator1;
	}

	public function getIf(): ?\Phi\Nodes\Expression
	{
		return $this->if;
	}

	public function hasIf(): bool
	{
		return $this->if !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $if
	 */
	public function setIf($if): void
	{
		if ($if !== null)
		{
			/** @var \Phi\Nodes\Expression $if */
			$if = NodeCoercer::coerce($if, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$if->detach();
			$if->parent = $this;
		}
		if ($this->if !== null)
		{
			$this->if->detach();
		}
		$this->if = $if;
	}

	public function getOperator2(): \Phi\Token
	{
		if ($this->operator2 === null)
		{
			throw TreeException::missingNode($this, "operator2");
		}
		return $this->operator2;
	}

	public function hasOperator2(): bool
	{
		return $this->operator2 !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $operator2
	 */
	public function setOperator2($operator2): void
	{
		if ($operator2 !== null)
		{
			/** @var \Phi\Token $operator2 */
			$operator2 = NodeCoercer::coerce($operator2, \Phi\Token::class, $this->getPhpVersion());
			$operator2->detach();
			$operator2->parent = $this;
		}
		if ($this->operator2 !== null)
		{
			$this->operator2->detach();
		}
		$this->operator2 = $operator2;
	}

	public function getElse(): \Phi\Nodes\Expression
	{
		if ($this->else === null)
		{
			throw TreeException::missingNode($this, "else");
		}
		return $this->else;
	}

	public function hasElse(): bool
	{
		return $this->else !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $else
	 */
	public function setElse($else): void
	{
		if ($else !== null)
		{
			/** @var \Phi\Nodes\Expression $else */
			$else = NodeCoercer::coerce($else, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$else->detach();
			$else->parent = $this;
		}
		if ($this->else !== null)
		{
			$this->else->detach();
		}
		$this->else = $else;
	}

	public function _validate(int $flags): void
	{
		if ($this->condition === null)
			throw ValidationException::missingChild($this, "condition");
		if ($this->operator1 === null)
			throw ValidationException::missingChild($this, "operator1");
		if ($this->operator2 === null)
			throw ValidationException::missingChild($this, "operator2");
		if ($this->else === null)
			throw ValidationException::missingChild($this, "else");
		if ($this->operator1->getType() !== 118)
			throw ValidationException::invalidSyntax($this->operator1, [118]);
		if ($this->operator2->getType() !== 113)
			throw ValidationException::invalidSyntax($this->operator2, [113]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->condition->_validate(1);
		if ($this->if)
			$this->if->_validate(1);
		$this->else->_validate(1);
	}

	public function _autocorrect(): void
	{
		if ($this->condition)
			$this->condition->_autocorrect();
		if (!$this->operator1)
			$this->setOperator1(new Token(118, '?'));
		if ($this->if)
			$this->if->_autocorrect();
		if (!$this->operator2)
			$this->setOperator2(new Token(113, ':'));
		if ($this->else)
			$this->else->_autocorrect();

		$this->extraAutocorrect();
	}
}
