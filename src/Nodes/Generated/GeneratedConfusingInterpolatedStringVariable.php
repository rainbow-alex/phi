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

trait GeneratedConfusingInterpolatedStringVariable
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftDelimiter;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $variable;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightDelimiter;

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
	 * @param \Phi\Token $leftDelimiter
	 * @param \Phi\Nodes\Expression $variable
	 * @param \Phi\Token $rightDelimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($leftDelimiter, $variable, $rightDelimiter)
	{
		$instance = new self;
	$instance->setLeftDelimiter($leftDelimiter);
	$instance->setVariable($variable);
	$instance->setRightDelimiter($rightDelimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftDelimiter,
			$this->variable,
			$this->rightDelimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftDelimiter === $childToDetach)
			return $this->leftDelimiter;
		if ($this->variable === $childToDetach)
			return $this->variable;
		if ($this->rightDelimiter === $childToDetach)
			return $this->rightDelimiter;
		throw new \LogicException();
	}

	public function getLeftDelimiter(): \Phi\Token
	{
		if ($this->leftDelimiter === null)
		{
			throw TreeException::missingNode($this, "leftDelimiter");
		}
		return $this->leftDelimiter;
	}

	public function hasLeftDelimiter(): bool
	{
		return $this->leftDelimiter !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $leftDelimiter
	 */
	public function setLeftDelimiter($leftDelimiter): void
	{
		if ($leftDelimiter !== null)
		{
			/** @var \Phi\Token $leftDelimiter */
			$leftDelimiter = NodeCoercer::coerce($leftDelimiter, \Phi\Token::class, $this->getPhpVersion());
			$leftDelimiter->detach();
			$leftDelimiter->parent = $this;
		}
		if ($this->leftDelimiter !== null)
		{
			$this->leftDelimiter->detach();
		}
		$this->leftDelimiter = $leftDelimiter;
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

	public function getRightDelimiter(): \Phi\Token
	{
		if ($this->rightDelimiter === null)
		{
			throw TreeException::missingNode($this, "rightDelimiter");
		}
		return $this->rightDelimiter;
	}

	public function hasRightDelimiter(): bool
	{
		return $this->rightDelimiter !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $rightDelimiter
	 */
	public function setRightDelimiter($rightDelimiter): void
	{
		if ($rightDelimiter !== null)
		{
			/** @var \Phi\Token $rightDelimiter */
			$rightDelimiter = NodeCoercer::coerce($rightDelimiter, \Phi\Token::class, $this->getPhpVersion());
			$rightDelimiter->detach();
			$rightDelimiter->parent = $this;
		}
		if ($this->rightDelimiter !== null)
		{
			$this->rightDelimiter->detach();
		}
		$this->rightDelimiter = $rightDelimiter;
	}

	public function _validate(int $flags): void
	{
		if ($this->leftDelimiter === null)
			throw ValidationException::missingChild($this, "leftDelimiter");
		if ($this->variable === null)
			throw ValidationException::missingChild($this, "variable");
		if ($this->rightDelimiter === null)
			throw ValidationException::missingChild($this, "rightDelimiter");
		if ($this->leftDelimiter->getType() !== 160)
			throw ValidationException::invalidSyntax($this->leftDelimiter, [160]);
		if ($this->rightDelimiter->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightDelimiter, [126]);


		$this->extraValidation($flags);

		$this->variable->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->leftDelimiter)
			$this->setLeftDelimiter(new Token(160, '${'));
		if ($this->variable)
			$this->variable->_autocorrect();
		if (!$this->rightDelimiter)
			$this->setRightDelimiter(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
