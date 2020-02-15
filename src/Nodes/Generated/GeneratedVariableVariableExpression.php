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

trait GeneratedVariableVariableExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $dollar;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $name;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $name
	 */
	public function __construct($name = null)
	{
		if ($name !== null)
		{
			$this->setName($name);
		}
	}

	/**
	 * @param \Phi\Token $dollar
	 * @param \Phi\Token|null $leftBrace
	 * @param \Phi\Nodes\Expression $name
	 * @param \Phi\Token|null $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($dollar, $leftBrace, $name, $rightBrace)
	{
		$instance = new self;
	$instance->setDollar($dollar);
	$instance->setLeftBrace($leftBrace);
	$instance->setName($name);
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->dollar,
			$this->leftBrace,
			$this->name,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->dollar === $childToDetach)
			return $this->dollar;
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->rightBrace === $childToDetach)
			return $this->rightBrace;
		throw new \LogicException();
	}

	public function getDollar(): \Phi\Token
	{
		if ($this->dollar === null)
		{
			throw TreeException::missingNode($this, "dollar");
		}
		return $this->dollar;
	}

	public function hasDollar(): bool
	{
		return $this->dollar !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $dollar
	 */
	public function setDollar($dollar): void
	{
		if ($dollar !== null)
		{
			/** @var \Phi\Token $dollar */
			$dollar = NodeCoercer::coerce($dollar, \Phi\Token::class, $this->getPhpVersion());
			$dollar->detach();
			$dollar->parent = $this;
		}
		if ($this->dollar !== null)
		{
			$this->dollar->detach();
		}
		$this->dollar = $dollar;
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

	public function getName(): \Phi\Nodes\Expression
	{
		if ($this->name === null)
		{
			throw TreeException::missingNode($this, "name");
		}
		return $this->name;
	}

	public function hasName(): bool
	{
		return $this->name !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $name
	 */
	public function setName($name): void
	{
		if ($name !== null)
		{
			/** @var \Phi\Nodes\Expression $name */
			$name = NodeCoercer::coerce($name, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$name->detach();
			$name->parent = $this;
		}
		if ($this->name !== null)
		{
			$this->name->detach();
		}
		$this->name = $name;
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
		if ($this->dollar === null)
			throw ValidationException::missingChild($this, "dollar");
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if ($this->dollar->getType() !== 102)
			throw ValidationException::invalidSyntax($this->dollar, [102]);
		if ($this->leftBrace)
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace)
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

		$this->name->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->dollar)
			$this->setDollar(new Token(102, '$'));
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		if ($this->name)
			$this->name->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
