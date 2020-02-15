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

trait GeneratedIfStatement
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
	private $condition;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @var \Phi\Nodes\Block|null
	 */
	private $block;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\Elseif_[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\Elseif_>
	 */
	private $elseifClauses;

	/**
	 * @var \Phi\Nodes\Statements\Else_|null
	 */
	private $elseClause;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $condition
	 * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
	 */
	public function __construct($condition = null, $block = null)
	{
		if ($condition !== null)
		{
			$this->setCondition($condition);
		}
		if ($block !== null)
		{
			$this->setBlock($block);
		}
		$this->elseifClauses = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statements\Elseif_::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $leftParenthesis
	 * @param \Phi\Nodes\Expression $condition
	 * @param \Phi\Token $rightParenthesis
	 * @param \Phi\Nodes\Block $block
	 * @param mixed[] $elseifClauses
	 * @param \Phi\Nodes\Statements\Else_|null $elseClause
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $leftParenthesis, $condition, $rightParenthesis, $block, $elseifClauses, $elseClause)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->setCondition($condition);
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setBlock($block);
	$instance->elseifClauses->__initUnchecked($elseifClauses);
	$instance->elseifClauses->parent = $instance;
	$instance->setElseClause($elseClause);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->leftParenthesis,
			$this->condition,
			$this->rightParenthesis,
			$this->block,
			$this->elseifClauses,
			$this->elseClause,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->condition === $childToDetach)
			return $this->condition;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		if ($this->block === $childToDetach)
			return $this->block;
		if ($this->elseifClauses === $childToDetach)
			return $this->elseifClauses;
		if ($this->elseClause === $childToDetach)
			return $this->elseClause;
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

	public function getLeftParenthesis(): \Phi\Token
	{
		if ($this->leftParenthesis === null)
		{
			throw TreeException::missingNode($this, "leftParenthesis");
		}
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

	public function getRightParenthesis(): \Phi\Token
	{
		if ($this->rightParenthesis === null)
		{
			throw TreeException::missingNode($this, "rightParenthesis");
		}
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

	public function getBlock(): \Phi\Nodes\Block
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
	 * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
	 */
	public function setBlock($block): void
	{
		if ($block !== null)
		{
			/** @var \Phi\Nodes\Block $block */
			$block = NodeCoercer::coerce($block, \Phi\Nodes\Block::class, $this->getPhpVersion());
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
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\Elseif_[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\Elseif_>
	 */
	public function getElseifClauses(): \Phi\Nodes\Base\NodesList
	{
		return $this->elseifClauses;
	}

	public function getElseClause(): ?\Phi\Nodes\Statements\Else_
	{
		return $this->elseClause;
	}

	public function hasElseClause(): bool
	{
		return $this->elseClause !== null;
	}

	/**
	 * @param \Phi\Nodes\Statements\Else_|\Phi\Node|string|null $elseClause
	 */
	public function setElseClause($elseClause): void
	{
		if ($elseClause !== null)
		{
			/** @var \Phi\Nodes\Statements\Else_ $elseClause */
			$elseClause = NodeCoercer::coerce($elseClause, \Phi\Nodes\Statements\Else_::class, $this->getPhpVersion());
			$elseClause->detach();
			$elseClause->parent = $this;
		}
		if ($this->elseClause !== null)
		{
			$this->elseClause->detach();
		}
		$this->elseClause = $elseClause;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->condition === null)
			throw ValidationException::missingChild($this, "condition");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->block === null)
			throw ValidationException::missingChild($this, "block");
		if ($this->keyword->getType() !== 191)
			throw ValidationException::invalidSyntax($this->keyword, [191]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


		$this->extraValidation($flags);

		$this->condition->_validate(1);
		$this->block->_validate(0);
		foreach ($this->elseifClauses as $t)
			$t->_validate(0);
		if ($this->elseClause)
			$this->elseClause->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(191, 'if'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		if ($this->condition)
			$this->condition->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if ($this->block)
			$this->block->_autocorrect();
		foreach ($this->elseifClauses as $t)
			$t->_autocorrect();
		if ($this->elseClause)
			$this->elseClause->_autocorrect();

		$this->extraAutocorrect();
	}
}
