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

trait GeneratedImplements
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
	 */
	private $names;

	/**
	 * @param \Phi\Nodes\Helpers\Name $name
	 */
	public function __construct($name = null)
	{
		$this->names = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Name::class);
		if ($name !== null)
		{
			$this->names->add($name);
		}
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param mixed[] $names
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $names)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->names->__initUnchecked($names);
	$instance->names->parent = $instance;
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->names,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->names === $childToDetach)
			return $this->names;
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Name[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Name>
	 */
	public function getNames(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->names;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->keyword->getType() !== 192)
			throw ValidationException::invalidSyntax($this->keyword, [192]);
		foreach ($this->names->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);


		$this->extraValidation($flags);

		foreach ($this->names as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(192, 'implements'));
		foreach ($this->names as $t)
			$t->_autocorrect();

		$this->extraAutocorrect();
	}
}
