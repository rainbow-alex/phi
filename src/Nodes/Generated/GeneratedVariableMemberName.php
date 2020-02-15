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

trait GeneratedVariableMemberName
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $expression;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $expression
	 */
	public function __construct($expression = null)
	{
		if ($expression !== null)
		{
			$this->setExpression($expression);
		}
	}

	/**
	 * @param \Phi\Token|null $leftBrace
	 * @param \Phi\Nodes\Expression $expression
	 * @param \Phi\Token|null $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($leftBrace, $expression, $rightBrace)
	{
		$instance = new self;
	$instance->setLeftBrace($leftBrace);
	$instance->setExpression($expression);
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftBrace,
			$this->expression,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->expression === $childToDetach)
			return $this->expression;
		if ($this->rightBrace === $childToDetach)
			return $this->rightBrace;
		throw new \LogicException();
	}

	public function getLeftBrace(): ?\Phi\Token
	{
		return $this->leftBrace;
	}

	public function hasLeftBrace(): bool
	{
		return $this->leftBrace !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $leftBrace
	 */
	public function setLeftBrace($leftBrace): void
	{
		if ($leftBrace !== null)
		{
			/** @var \Phi\Token $leftBrace */
			$leftBrace = NodeCoercer::coerce($leftBrace, \Phi\Token::class, $this->getPhpVersion());
			$leftBrace->detach();
			$leftBrace->parent = $this;
		}
		if ($this->leftBrace !== null)
		{
			$this->leftBrace->detach();
		}
		$this->leftBrace = $leftBrace;
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

	public function getRightBrace(): ?\Phi\Token
	{
		return $this->rightBrace;
	}

	public function hasRightBrace(): bool
	{
		return $this->rightBrace !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $rightBrace
	 */
	public function setRightBrace($rightBrace): void
	{
		if ($rightBrace !== null)
		{
			/** @var \Phi\Token $rightBrace */
			$rightBrace = NodeCoercer::coerce($rightBrace, \Phi\Token::class, $this->getPhpVersion());
			$rightBrace->detach();
			$rightBrace->parent = $this;
		}
		if ($this->rightBrace !== null)
		{
			$this->rightBrace->detach();
		}
		$this->rightBrace = $rightBrace;
	}

	public function _validate(int $flags): void
	{
		if ($this->expression === null)
			throw ValidationException::missingChild($this, "expression");
		if ($this->leftBrace)
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace)
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

		$this->expression->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		if ($this->expression)
			$this->expression->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
