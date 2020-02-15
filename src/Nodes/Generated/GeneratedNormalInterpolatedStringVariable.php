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

trait GeneratedNormalInterpolatedStringVariable
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $variable;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $variable
	 */
	public function __construct($variable = null)
	{
		if ($variable !== null)
		{
			$this->setVariable($variable);
		}
	}

	/**
	 * @param \Phi\Token|null $leftBrace
	 * @param \Phi\Nodes\Expression $variable
	 * @param \Phi\Token|null $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($leftBrace, $variable, $rightBrace)
	{
		$instance = new self;
	$instance->setLeftBrace($leftBrace);
	$instance->setVariable($variable);
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftBrace,
			$this->variable,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->variable === $childToDetach)
			return $this->variable;
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

	public function getVariable(): \Phi\Nodes\Expression
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
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $variable
	 */
	public function setVariable($variable): void
	{
		if ($variable !== null)
		{
			/** @var \Phi\Nodes\Expression $variable */
			$variable = NodeCoercer::coerce($variable, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$variable->detach();
			$variable->parent = $this;
		}
		if ($this->variable !== null)
		{
			$this->variable->detach();
		}
		$this->variable = $variable;
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
		if ($this->variable === null)
			throw ValidationException::missingChild($this, "variable");
		if ($this->leftBrace)
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace)
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

		$this->variable->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		if ($this->variable)
			$this->variable->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
