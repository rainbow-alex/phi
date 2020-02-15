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

trait GeneratedTryStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Blocks\RegularBlock|null
	 */
	private $block;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\Catch_[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\Catch_>
	 */
	private $catchClauses;

	/**
	 * @var \Phi\Nodes\Statements\Finally_|null
	 */
	private $finallyClause;

	/**
	 */
	public function __construct()
	{
		$this->catchClauses = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statements\Catch_::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Nodes\Blocks\RegularBlock $block
	 * @param mixed[] $catchClauses
	 * @param \Phi\Nodes\Statements\Finally_|null $finallyClause
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $block, $catchClauses, $finallyClause)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setBlock($block);
	$instance->catchClauses->__initUnchecked($catchClauses);
	$instance->catchClauses->parent = $instance;
	$instance->setFinallyClause($finallyClause);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->block,
			$this->catchClauses,
			$this->finallyClause,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->block === $childToDetach)
			return $this->block;
		if ($this->catchClauses === $childToDetach)
			return $this->catchClauses;
		if ($this->finallyClause === $childToDetach)
			return $this->finallyClause;
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

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\Catch_[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\Catch_>
	 */
	public function getCatchClauses(): \Phi\Nodes\Base\NodesList
	{
		return $this->catchClauses;
	}

	public function getFinallyClause(): ?\Phi\Nodes\Statements\Finally_
	{
		return $this->finallyClause;
	}

	public function hasFinallyClause(): bool
	{
		return $this->finallyClause !== null;
	}

	/**
	 * @param \Phi\Nodes\Statements\Finally_|\Phi\Node|string|null $finallyClause
	 */
	public function setFinallyClause($finallyClause): void
	{
		if ($finallyClause !== null)
		{
			/** @var \Phi\Nodes\Statements\Finally_ $finallyClause */
			$finallyClause = NodeCoercer::coerce($finallyClause, \Phi\Nodes\Statements\Finally_::class, $this->getPhpVersion());
			$finallyClause->detach();
			$finallyClause->parent = $this;
		}
		if ($this->finallyClause !== null)
		{
			$this->finallyClause->detach();
		}
		$this->finallyClause = $finallyClause;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->block === null)
			throw ValidationException::missingChild($this, "block");
		if ($this->keyword->getType() !== 252)
			throw ValidationException::invalidSyntax($this->keyword, [252]);


		$this->extraValidation($flags);

		$this->block->_validate(0);
		foreach ($this->catchClauses as $t)
			$t->_validate(0);
		if ($this->finallyClause)
			$this->finallyClause->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(252, 'try'));
		if ($this->block)
			$this->block->_autocorrect();
		foreach ($this->catchClauses as $t)
			$t->_autocorrect();
		if ($this->finallyClause)
			$this->finallyClause->_autocorrect();

		$this->extraAutocorrect();
	}
}
