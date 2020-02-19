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

trait GeneratedExececutionExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftDelimiter;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart>
	 */
	private $parts;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightDelimiter;

	/**
	 */
	public function __construct()
	{
		$this->parts = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart::class);
	}

	/**
	 * @param \Phi\Token $leftDelimiter
	 * @param mixed[] $parts
	 * @param \Phi\Token $rightDelimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($leftDelimiter, $parts, $rightDelimiter)
	{
		$instance = new self;
	$instance->setLeftDelimiter($leftDelimiter);
	$instance->parts->__initUnchecked($parts);
	$instance->parts->parent = $instance;
	$instance->setRightDelimiter($rightDelimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftDelimiter,
			$this->parts,
			$this->rightDelimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftDelimiter === $childToDetach)
			return $this->leftDelimiter;
		if ($this->parts === $childToDetach)
			return $this->parts;
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

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Expressions\StringInterpolation\InterpolatedStringPart>
	 */
	public function getParts(): \Phi\Nodes\Base\NodesList
	{
		return $this->parts;
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
		if ($this->rightDelimiter === null)
			throw ValidationException::missingChild($this, "rightDelimiter");
		if ($this->leftDelimiter->getType() !== 123)
			throw ValidationException::invalidSyntax($this->leftDelimiter, [123]);
		if ($this->rightDelimiter->getType() !== 123)
			throw ValidationException::invalidSyntax($this->rightDelimiter, [123]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		foreach ($this->parts as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->leftDelimiter)
			$this->setLeftDelimiter(new Token(123, '`'));
		foreach ($this->parts as $t)
			$t->_autocorrect();
		if (!$this->rightDelimiter)
			$this->setRightDelimiter(new Token(123, '`'));

		$this->extraAutocorrect();
	}
}
