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

trait GeneratedArrayAccessExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $expression;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftBracket;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $index;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBracket;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $index
	 */
	public function __construct($expression = null, $index = null)
	{
		if ($expression !== null)
		{
			$this->setExpression($expression);
		}
		if ($index !== null)
		{
			$this->setIndex($index);
		}
	}

	/**
	 * @param \Phi\Nodes\Expression $expression
	 * @param \Phi\Token $leftBracket
	 * @param \Phi\Nodes\Expression|null $index
	 * @param \Phi\Token $rightBracket
	 * @return self
	 */
	public static function __instantiateUnchecked($expression, $leftBracket, $index, $rightBracket)
	{
		$instance = new self;
	$instance->setExpression($expression);
	$instance->setLeftBracket($leftBracket);
	$instance->setIndex($index);
	$instance->setRightBracket($rightBracket);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->expression,
			$this->leftBracket,
			$this->index,
			$this->rightBracket,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->expression === $childToDetach)
			return $this->expression;
		if ($this->leftBracket === $childToDetach)
			return $this->leftBracket;
		if ($this->index === $childToDetach)
			return $this->index;
		if ($this->rightBracket === $childToDetach)
			return $this->rightBracket;
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

	public function getLeftBracket(): \Phi\Token
	{
		if ($this->leftBracket === null)
		{
			throw TreeException::missingNode($this, "leftBracket");
		}
		return $this->leftBracket;
	}

	public function hasLeftBracket(): bool
	{
		return $this->leftBracket !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $leftBracket
	 */
	public function setLeftBracket($leftBracket): void
	{
		if ($leftBracket !== null)
		{
			/** @var \Phi\Token $leftBracket */
			$leftBracket = NodeCoercer::coerce($leftBracket, \Phi\Token::class, $this->getPhpVersion());
			$leftBracket->detach();
			$leftBracket->parent = $this;
		}
		if ($this->leftBracket !== null)
		{
			$this->leftBracket->detach();
		}
		$this->leftBracket = $leftBracket;
	}

	public function getIndex(): ?\Phi\Nodes\Expression
	{
		return $this->index;
	}

	public function hasIndex(): bool
	{
		return $this->index !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $index
	 */
	public function setIndex($index): void
	{
		if ($index !== null)
		{
			/** @var \Phi\Nodes\Expression $index */
			$index = NodeCoercer::coerce($index, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$index->detach();
			$index->parent = $this;
		}
		if ($this->index !== null)
		{
			$this->index->detach();
		}
		$this->index = $index;
	}

	public function getRightBracket(): \Phi\Token
	{
		if ($this->rightBracket === null)
		{
			throw TreeException::missingNode($this, "rightBracket");
		}
		return $this->rightBracket;
	}

	public function hasRightBracket(): bool
	{
		return $this->rightBracket !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $rightBracket
	 */
	public function setRightBracket($rightBracket): void
	{
		if ($rightBracket !== null)
		{
			/** @var \Phi\Token $rightBracket */
			$rightBracket = NodeCoercer::coerce($rightBracket, \Phi\Token::class, $this->getPhpVersion());
			$rightBracket->detach();
			$rightBracket->parent = $this;
		}
		if ($this->rightBracket !== null)
		{
			$this->rightBracket->detach();
		}
		$this->rightBracket = $rightBracket;
	}

	public function _validate(int $flags): void
	{
		if ($this->expression === null)
			throw ValidationException::missingChild($this, "expression");
		if ($this->leftBracket === null)
			throw ValidationException::missingChild($this, "leftBracket");
		if ($this->rightBracket === null)
			throw ValidationException::missingChild($this, "rightBracket");
		if ($this->leftBracket->getType() !== 120)
			throw ValidationException::invalidSyntax($this->leftBracket, [120]);
		if ($this->rightBracket->getType() !== 121)
			throw ValidationException::invalidSyntax($this->rightBracket, [121]);


		$this->extraValidation($flags);

		$this->expression->_validate(1);
		if ($this->index)
			$this->index->_validate(1);
	}

	public function _autocorrect(): void
	{
		if ($this->expression)
			$this->expression->_autocorrect();
		if (!$this->leftBracket)
			$this->setLeftBracket(new Token(120, '['));
		if ($this->index)
			$this->index->_autocorrect();
		if (!$this->rightBracket)
			$this->setRightBracket(new Token(121, ']'));

		$this->extraAutocorrect();
	}
}
