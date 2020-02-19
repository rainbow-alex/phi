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

trait GeneratedNormalInterpolatedStringVariableArrayAccess
{
	/**
	 * @var \Phi\Nodes\Expressions\NormalVariableExpression|null
	 */
	private $variable;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftBracket;

	/**
	 * @var \Phi\Token|null
	 */
	private $minus;

	/**
	 * @var \Phi\Token|null
	 */
	private $index;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBracket;

	/**
	 * @param \Phi\Nodes\Expressions\NormalVariableExpression|\Phi\Node|string|null $variable
	 * @param \Phi\Token|\Phi\Node|string|null $index
	 */
	public function __construct($variable = null, $index = null)
	{
		if ($variable !== null)
		{
			$this->setVariable($variable);
		}
		if ($index !== null)
		{
			$this->setIndex($index);
		}
	}

	/**
	 * @param \Phi\Nodes\Expressions\NormalVariableExpression $variable
	 * @param \Phi\Token $leftBracket
	 * @param \Phi\Token|null $minus
	 * @param \Phi\Token $index
	 * @param \Phi\Token $rightBracket
	 * @return self
	 */
	public static function __instantiateUnchecked($variable, $leftBracket, $minus, $index, $rightBracket)
	{
		$instance = new self;
	$instance->setVariable($variable);
	$instance->setLeftBracket($leftBracket);
	$instance->setMinus($minus);
	$instance->setIndex($index);
	$instance->setRightBracket($rightBracket);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->variable,
			$this->leftBracket,
			$this->minus,
			$this->index,
			$this->rightBracket,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->variable === $childToDetach)
			return $this->variable;
		if ($this->leftBracket === $childToDetach)
			return $this->leftBracket;
		if ($this->minus === $childToDetach)
			return $this->minus;
		if ($this->index === $childToDetach)
			return $this->index;
		if ($this->rightBracket === $childToDetach)
			return $this->rightBracket;
		throw new \LogicException();
	}

	public function getVariable(): \Phi\Nodes\Expressions\NormalVariableExpression
	{
		if ($this->variable === null)
		{
			throw TreeException::missingNode($this, "variable");
		}
		return $this->variable;
	}

	public function hasVariable(): bool
	{
		return $this->variable !== null;
	}

	/**
	 * @param \Phi\Nodes\Expressions\NormalVariableExpression|\Phi\Node|string|null $variable
	 */
	public function setVariable($variable): void
	{
		if ($variable !== null)
		{
			/** @var \Phi\Nodes\Expressions\NormalVariableExpression $variable */
			$variable = NodeCoercer::coerce($variable, \Phi\Nodes\Expressions\NormalVariableExpression::class, $this->getPhpVersion());
			$variable->detach();
			$variable->parent = $this;
		}
		if ($this->variable !== null)
		{
			$this->variable->detach();
		}
		$this->variable = $variable;
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

	public function getMinus(): ?\Phi\Token
	{
		return $this->minus;
	}

	public function hasMinus(): bool
	{
		return $this->minus !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $minus
	 */
	public function setMinus($minus): void
	{
		if ($minus !== null)
		{
			/** @var \Phi\Token $minus */
			$minus = NodeCoercer::coerce($minus, \Phi\Token::class, $this->getPhpVersion());
			$minus->detach();
			$minus->parent = $this;
		}
		if ($this->minus !== null)
		{
			$this->minus->detach();
		}
		$this->minus = $minus;
	}

	public function getIndex(): \Phi\Token
	{
		if ($this->index === null)
		{
			throw TreeException::missingNode($this, "index");
		}
		return $this->index;
	}

	public function hasIndex(): bool
	{
		return $this->index !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $index
	 */
	public function setIndex($index): void
	{
		if ($index !== null)
		{
			/** @var \Phi\Token $index */
			$index = NodeCoercer::coerce($index, \Phi\Token::class, $this->getPhpVersion());
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
		if ($this->variable === null)
			throw ValidationException::missingChild($this, "variable");
		if ($this->leftBracket === null)
			throw ValidationException::missingChild($this, "leftBracket");
		if ($this->index === null)
			throw ValidationException::missingChild($this, "index");
		if ($this->rightBracket === null)
			throw ValidationException::missingChild($this, "rightBracket");
		if ($this->leftBracket->getType() !== 120)
			throw ValidationException::invalidSyntax($this->leftBracket, [120]);
		if ($this->minus)
		if ($this->minus->getType() !== 110)
			throw ValidationException::invalidSyntax($this->minus, [110]);
		if (!\in_array($this->index->getType(), [222, 245, 257], true))
			throw ValidationException::invalidSyntax($this->index, [222, 245, 257]);
		if ($this->rightBracket->getType() !== 121)
			throw ValidationException::invalidSyntax($this->rightBracket, [121]);


		$this->extraValidation($flags);

		$this->variable->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->variable)
			$this->variable->_autocorrect();
		if (!$this->leftBracket)
			$this->setLeftBracket(new Token(120, '['));
		if (!$this->minus)
			$this->setMinus(new Token(110, '-'));
		if (!$this->rightBracket)
			$this->setRightBracket(new Token(121, ']'));

		$this->extraAutocorrect();
	}
}
