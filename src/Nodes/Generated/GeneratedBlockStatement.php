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

trait GeneratedBlockStatement
{
	/**
	 * @var \Phi\Nodes\Blocks\RegularBlock|null
	 */
	private $block;

	/**
	 * @param \Phi\Nodes\Blocks\RegularBlock|\Phi\Node|string|null $block
	 */
	public function __construct($block = null)
	{
		if ($block !== null)
		{
			$this->setBlock($block);
		}
	}

	/**
	 * @param \Phi\Nodes\Blocks\RegularBlock $block
	 * @return self
	 */
	public static function __instantiateUnchecked($block)
	{
		$instance = new self;
	$instance->setBlock($block);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->block,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->block === $childToDetach)
			return $this->block;
		throw new \LogicException();
	}

	public function getBlock(): \Phi\Nodes\Blocks\RegularBlock
	{
		if ($this->block === null)
		{
			throw TreeException::missingNode($this, "block");
		}
		return $this->block;
	}

	public function hasBlock(): bool
	{
		return $this->block !== null;
	}

	/**
	 * @param \Phi\Nodes\Blocks\RegularBlock|\Phi\Node|string|null $block
	 */
	public function setBlock($block): void
	{
		if ($block !== null)
		{
			/** @var \Phi\Nodes\Blocks\RegularBlock $block */
			$block = NodeCoercer::coerce($block, \Phi\Nodes\Blocks\RegularBlock::class, $this->getPhpVersion());
			$block->detach();
			$block->parent = $this;
		}
		if ($this->block !== null)
		{
			$this->block->detach();
		}
		$this->block = $block;
	}

	public function _validate(int $flags): void
	{
		if ($this->block === null)
			throw ValidationException::missingChild($this, "block");


		$this->extraValidation($flags);

		$this->block->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->block)
			$this->block->_autocorrect();

		$this->extraAutocorrect();
	}
}
