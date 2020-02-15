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

trait GeneratedStaticVariableStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Statements\StaticVariable[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Statements\StaticVariable>
	 */
	private $variables;

	/**
	 * @var \Phi\Token|null
	 */
	private $delimiter;

	/**
	 */
	public function __construct()
	{
		$this->variables = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Statements\StaticVariable::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param mixed[] $variables
	 * @param \Phi\Token $delimiter
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $variables, $delimiter)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->variables->__initUnchecked($variables);
	$instance->variables->parent = $instance;
	$instance->setDelimiter($delimiter);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->variables,
			$this->delimiter,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->variables === $childToDetach)
			return $this->variables;
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
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Statements\StaticVariable[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Statements\StaticVariable>
	 */
	public function getVariables(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->variables;
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
		if ($this->keyword->getType() !== 244)
			throw ValidationException::invalidSyntax($this->keyword, [244]);
		foreach ($this->variables->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if (!\in_array($this->delimiter->getType(), [114, 143], true))
			throw ValidationException::invalidSyntax($this->delimiter, [114, 143]);


		$this->extraValidation($flags);

		foreach ($this->variables as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(244, 'static'));
		foreach ($this->variables as $t)
			$t->_autocorrect();

		$this->extraAutocorrect();
	}
}
