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

trait GeneratedIssetExpression
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
	private $expressions;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @param \Phi\Nodes\Expression $expression
	 */
	public function __construct($expression = null)
	{
		$this->expressions = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expression::class);
		if ($expression !== null)
		{
			$this->expressions->add($expression);
		}
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $leftParenthesis
	 * @param mixed[] $expressions
	 * @param \Phi\Token $rightParenthesis
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $leftParenthesis, $expressions, $rightParenthesis)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->expressions->__initUnchecked($expressions);
	$instance->expressions->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->leftParenthesis,
			$this->expressions,
			$this->rightParenthesis,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->expressions === $childToDetach)
			return $this->expressions;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
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
	public function getExpressions(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->expressions;
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

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->keyword->getType() !== 201)
			throw ValidationException::invalidSyntax($this->keyword, [201]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->expressions->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		foreach ($this->expressions as $t)
			$t->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(201, 'isset'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->expressions as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));

		$this->extraAutocorrect();
	}
}
