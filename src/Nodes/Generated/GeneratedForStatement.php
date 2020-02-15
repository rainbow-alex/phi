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

trait GeneratedForStatement
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
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	private $initExpressions;

	/**
	 * @var \Phi\Token|null
	 */
	private $separator1;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	private $conditionExpressions;

	/**
	 * @var \Phi\Token|null
	 */
	private $separator2;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	private $stepExpressions;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @var \Phi\Nodes\Block|null
	 */
	private $block;

	/**
	 * @param \Phi\Nodes\Expression $initExpression
	 * @param \Phi\Nodes\Expression $conditionExpression
	 * @param \Phi\Nodes\Expression $stepExpression
	 * @param \Phi\Nodes\Block|\Phi\Node|string|null $block
	 */
	public function __construct($initExpression = null, $conditionExpression = null, $stepExpression = null, $block = null)
	{
		$this->initExpressions = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expression::class);
		if ($initExpression !== null)
		{
			$this->initExpressions->add($initExpression);
		}
		$this->conditionExpressions = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expression::class);
		if ($conditionExpression !== null)
		{
			$this->conditionExpressions->add($conditionExpression);
		}
		$this->stepExpressions = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expression::class);
		if ($stepExpression !== null)
		{
			$this->stepExpressions->add($stepExpression);
		}
		if ($block !== null)
		{
			$this->setBlock($block);
		}
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $leftParenthesis
	 * @param mixed[] $initExpressions
	 * @param \Phi\Token $separator1
	 * @param mixed[] $conditionExpressions
	 * @param \Phi\Token $separator2
	 * @param mixed[] $stepExpressions
	 * @param \Phi\Token $rightParenthesis
	 * @param \Phi\Nodes\Block $block
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $leftParenthesis, $initExpressions, $separator1, $conditionExpressions, $separator2, $stepExpressions, $rightParenthesis, $block)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->initExpressions->__initUnchecked($initExpressions);
	$instance->initExpressions->parent = $instance;
	$instance->setSeparator1($separator1);
	$instance->conditionExpressions->__initUnchecked($conditionExpressions);
	$instance->conditionExpressions->parent = $instance;
	$instance->setSeparator2($separator2);
	$instance->stepExpressions->__initUnchecked($stepExpressions);
	$instance->stepExpressions->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setBlock($block);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->leftParenthesis,
			$this->initExpressions,
			$this->separator1,
			$this->conditionExpressions,
			$this->separator2,
			$this->stepExpressions,
			$this->rightParenthesis,
			$this->block,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->initExpressions === $childToDetach)
			return $this->initExpressions;
		if ($this->separator1 === $childToDetach)
			return $this->separator1;
		if ($this->conditionExpressions === $childToDetach)
			return $this->conditionExpressions;
		if ($this->separator2 === $childToDetach)
			return $this->separator2;
		if ($this->stepExpressions === $childToDetach)
			return $this->stepExpressions;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		if ($this->block === $childToDetach)
			return $this->block;
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	public function getInitExpressions(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->initExpressions;
	}

	public function getSeparator1(): \Phi\Token
	{
		if ($this->separator1 === null)
		{
			throw TreeException::missingNode($this, "separator1");
		}
		return $this->separator1;
	}

	public function hasSeparator1(): bool
	{
		return $this->separator1 !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $separator1
	 */
	public function setSeparator1($separator1): void
	{
		if ($separator1 !== null)
		{
			/** @var \Phi\Token $separator1 */
			$separator1 = NodeCoercer::coerce($separator1, \Phi\Token::class, $this->getPhpVersion());
			$separator1->detach();
			$separator1->parent = $this;
		}
		if ($this->separator1 !== null)
		{
			$this->separator1->detach();
		}
		$this->separator1 = $separator1;
	}

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	public function getConditionExpressions(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->conditionExpressions;
	}

	public function getSeparator2(): \Phi\Token
	{
		if ($this->separator2 === null)
		{
			throw TreeException::missingNode($this, "separator2");
		}
		return $this->separator2;
	}

	public function hasSeparator2(): bool
	{
		return $this->separator2 !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $separator2
	 */
	public function setSeparator2($separator2): void
	{
		if ($separator2 !== null)
		{
			/** @var \Phi\Token $separator2 */
			$separator2 = NodeCoercer::coerce($separator2, \Phi\Token::class, $this->getPhpVersion());
			$separator2->detach();
			$separator2->parent = $this;
		}
		if ($this->separator2 !== null)
		{
			$this->separator2->detach();
		}
		$this->separator2 = $separator2;
	}

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	public function getStepExpressions(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->stepExpressions;
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

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->separator1 === null)
			throw ValidationException::missingChild($this, "separator1");
		if ($this->separator2 === null)
			throw ValidationException::missingChild($this, "separator2");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->block === null)
			throw ValidationException::missingChild($this, "block");
		if ($this->keyword->getType() !== 184)
			throw ValidationException::invalidSyntax($this->keyword, [184]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->initExpressions->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->separator1->getType() !== 114)
			throw ValidationException::invalidSyntax($this->separator1, [114]);
		foreach ($this->conditionExpressions->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->separator2->getType() !== 114)
			throw ValidationException::invalidSyntax($this->separator2, [114]);
		foreach ($this->stepExpressions->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);


		$this->extraValidation($flags);

		foreach ($this->initExpressions as $t)
			$t->_validate(1);
		foreach ($this->conditionExpressions as $t)
			$t->_validate(1);
		foreach ($this->stepExpressions as $t)
			$t->_validate(1);
		$this->block->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(184, 'for'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->initExpressions as $t)
			$t->_autocorrect();
		if (!$this->separator1)
			$this->setSeparator1(new Token(114, ';'));
		foreach ($this->conditionExpressions as $t)
			$t->_autocorrect();
		if (!$this->separator2)
			$this->setSeparator2(new Token(114, ';'));
		foreach ($this->stepExpressions as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if ($this->block)
			$this->block->_autocorrect();

		$this->extraAutocorrect();
	}
}
