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

trait GeneratedShortArrayExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftBracket;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expressions\ArrayItem[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expressions\ArrayItem>
	 */
	private $items;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBracket;

	/**
	 * @param \Phi\Nodes\Expressions\ArrayItem $item
	 */
	public function __construct($item = null)
	{
		$this->items = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expressions\ArrayItem::class);
		if ($item !== null)
		{
			$this->items->add($item);
		}
	}

	/**
	 * @param \Phi\Token $leftBracket
	 * @param mixed[] $items
	 * @param \Phi\Token $rightBracket
	 * @return self
	 */
	public static function __instantiateUnchecked($leftBracket, $items, $rightBracket)
	{
		$instance = new self;
	$instance->setLeftBracket($leftBracket);
	$instance->items->__initUnchecked($items);
	$instance->items->parent = $instance;
	$instance->setRightBracket($rightBracket);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftBracket,
			$this->items,
			$this->rightBracket,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftBracket === $childToDetach)
			return $this->leftBracket;
		if ($this->items === $childToDetach)
			return $this->items;
		if ($this->rightBracket === $childToDetach)
			return $this->rightBracket;
		throw new \LogicException();
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expressions\ArrayItem[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expressions\ArrayItem>
	 */
	public function getItems(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->items;
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
		if ($this->leftBracket === null)
			throw ValidationException::missingChild($this, "leftBracket");
		if ($this->rightBracket === null)
			throw ValidationException::missingChild($this, "rightBracket");
		if ($this->leftBracket->getType() !== 120)
			throw ValidationException::invalidSyntax($this->leftBracket, [120]);
		foreach ($this->items->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightBracket->getType() !== 121)
			throw ValidationException::invalidSyntax($this->rightBracket, [121]);

		if ($flags & 12)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{
		if (!$this->leftBracket)
			$this->setLeftBracket(new Token(120, '['));
		foreach ($this->items as $t)
			$t->_autocorrect();
		if (!$this->rightBracket)
			$this->setRightBracket(new Token(121, ']'));

		$this->extraAutocorrect();
	}
}
