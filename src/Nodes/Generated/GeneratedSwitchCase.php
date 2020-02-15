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

trait GeneratedSwitchCase
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $value;

	/**
	 * @var \Phi\Token|null
	 */
	private $colon;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
	 */
	private $statements;

	/**
	 */
	public function __construct()
	{
		$this->statements = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statement::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Nodes\Expression|null $value
	 * @param \Phi\Token $colon
	 * @param mixed[] $statements
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $value, $colon, $statements)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setValue($value);
	$instance->setColon($colon);
	$instance->statements->__initUnchecked($statements);
	$instance->statements->parent = $instance;
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->value,
			$this->colon,
			$this->statements,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->value === $childToDetach)
			return $this->value;
		if ($this->colon === $childToDetach)
			return $this->colon;
		if ($this->statements === $childToDetach)
			return $this->statements;
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

	public function getValue(): ?\Phi\Nodes\Expression
	{
		return $this->value;
	}

	public function hasValue(): bool
	{
		return $this->value !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $value
	 */
	public function setValue($value): void
	{
		if ($value !== null)
		{
			/** @var \Phi\Nodes\Expression $value */
			$value = NodeCoercer::coerce($value, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$value->detach();
			$value->parent = $this;
		}
		if ($this->value !== null)
		{
			$this->value->detach();
		}
		$this->value = $value;
	}

	public function getColon(): \Phi\Token
	{
		if ($this->colon === null)
		{
			throw TreeException::missingNode($this, "colon");
		}
		return $this->colon;
	}

	public function hasColon(): bool
	{
		return $this->colon !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $colon
	 */
	public function setColon($colon): void
	{
		if ($colon !== null)
		{
			/** @var \Phi\Token $colon */
			$colon = NodeCoercer::coerce($colon, \Phi\Token::class, $this->getPhpVersion());
			$colon->detach();
			$colon->parent = $this;
		}
		if ($this->colon !== null)
		{
			$this->colon->detach();
		}
		$this->colon = $colon;
	}

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
	 */
	public function getStatements(): \Phi\Nodes\Base\NodesList
	{
		return $this->statements;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->colon === null)
			throw ValidationException::missingChild($this, "colon");
		if (!\in_array($this->keyword->getType(), [138, 154], true))
			throw ValidationException::invalidSyntax($this->keyword, [138, 154]);
		if (!\in_array($this->colon->getType(), [113, 114], true))
			throw ValidationException::invalidSyntax($this->colon, [113, 114]);


		$this->extraValidation($flags);

		if ($this->value)
			$this->value->_validate(1);
		foreach ($this->statements as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->value)
			$this->value->_autocorrect();
		foreach ($this->statements as $t)
			$t->_autocorrect();

		$this->extraAutocorrect();
	}
}
