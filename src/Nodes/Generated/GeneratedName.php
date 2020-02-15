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

trait GeneratedName
{
	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	private $parts;

	/**
	 * @param \Phi\Token $part
	 */
	public function __construct($part = null)
	{
		$this->parts = new \Phi\Nodes\Base\NodesList(\Phi\Token::class);
		if ($part !== null)
		{
			$this->parts->add($part);
		}
	}

	/**
	 * @param mixed[] $parts
	 * @return self
	 */
	public static function __instantiateUnchecked($parts)
	{
		$instance = new self;
	$instance->parts->__initUnchecked($parts);
	$instance->parts->parent = $instance;
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->parts,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->parts === $childToDetach)
			return $this->parts;
		throw new \LogicException();
	}

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	public function getParts(): \Phi\Nodes\Base\NodesList
	{
		return $this->parts;
	}

	public function _validate(int $flags): void
	{
		foreach ($this->parts as $t)
			if (!\in_array($t->getType(), [245, 221, 244, 130, 137], true))
				throw ValidationException::invalidSyntax($t, [245, 221, 244, 130, 137]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{

		$this->extraAutocorrect();
	}
}
