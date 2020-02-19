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
	 * @var \Phi\Nodes\Expression|null
	 */
	private $variable;

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
	 * @param \Phi\Nodes\Expression $variable
	 * @return self
	 */
	public static function __instantiateUnchecked($variable)
	{
		$instance = new self;
	$instance->setVariable($variable);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->variable,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->variable === $childToDetach)
			return $this->variable;
		throw new \LogicException();
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

	public function _validate(int $flags): void
	{
		if ($this->variable === null)
			throw ValidationException::missingChild($this, "variable");


		$this->extraValidation($flags);

		$this->variable->_validate(1);
	}

	public function _autocorrect(): void
	{
		if ($this->variable)
			$this->variable->_autocorrect();

		$this->extraAutocorrect();
	}
}
