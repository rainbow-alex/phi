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

trait GeneratedEchoStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	private $expressions;

	/**
	 * @var \Phi\Token|null
	 */
	private $delimiter;

	/**
	 */
	public function __construct()
	{
		$this->expressions = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Expression::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param mixed[] $expressions
	 * @param \Phi\Token $delimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $expressions, $delimiter)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->expressions->__initUnchecked($expressions);
	$instance->expressions->parent = $instance;
	$instance->setDelimiter($delimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->expressions,
			$this->delimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->expressions === $childToDetach)
			return $this->expressions;
		if ($this->delimiter === $childToDetach)
			return $this->delimiter;
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
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Expression[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Expression>
	 */
	public function getExpressions(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->expressions;
	}

	public function getDelimiter(): \Phi\Token
	{
		if ($this->delimiter === null)
		{
			throw TreeException::missingNode($this, "delimiter");
		}
		return $this->delimiter;
	}

	public function hasDelimiter(): bool
	{
		return $this->delimiter !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $delimiter
	 */
	public function setDelimiter($delimiter): void
	{
		if ($delimiter !== null)
		{
			/** @var \Phi\Token $delimiter */
			$delimiter = NodeCoercer::coerce($delimiter, \Phi\Token::class, $this->getPhpVersion());
			$delimiter->detach();
			$delimiter->parent = $this;
		}
		if ($this->delimiter !== null)
		{
			$this->delimiter->detach();
		}
		$this->delimiter = $delimiter;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->delimiter === null)
			throw ValidationException::missingChild($this, "delimiter");
		if (!\in_array($this->keyword->getType(), [164, 226], true))
			throw ValidationException::invalidSyntax($this->keyword, [164, 226]);
		foreach ($this->expressions->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if (!\in_array($this->delimiter->getType(), [114, 143], true))
			throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


		$this->extraValidation($flags);

		foreach ($this->expressions as $t)
			$t->_validate(1);
	}

	public function _autocorrect(): void
	{
		foreach ($this->expressions as $t)
			$t->_autocorrect();

		$this->extraAutocorrect();
	}
}
