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

trait GeneratedRegularBlock
{
	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
	 */
	private $statements;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 * @param \Phi\Nodes\Statement $statement
	 */
	public function __construct($statement = null)
	{
		$this->statements = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statement::class);
		if ($statement !== null)
		{
			$this->statements->add($statement);
		}
	}

	/**
	 * @param \Phi\Token $leftBrace
	 * @param mixed[] $statements
	 * @param \Phi\Token $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($leftBrace, $statements, $rightBrace)
	{
		$instance = new self;
	$instance->setLeftBrace($leftBrace);
	$instance->statements->__initUnchecked($statements);
	$instance->statements->parent = $instance;
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->leftBrace,
			$this->statements,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->statements === $childToDetach)
			return $this->statements;
		if ($this->rightBrace === $childToDetach)
			return $this->rightBrace;
		throw new \LogicException();
	}

	public function getLeftBrace(): \Phi\Token
	{
		if ($this->leftBrace === null)
		{
			throw TreeException::missingNode($this, "leftBrace");
		}
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

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statement[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statement>
	 */
	public function getStatements(): \Phi\Nodes\Base\NodesList
	{
		return $this->statements;
	}

	public function getRightBrace(): \Phi\Token
	{
		if ($this->rightBrace === null)
		{
			throw TreeException::missingNode($this, "rightBrace");
		}
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
		if ($this->leftBrace === null)
			throw ValidationException::missingChild($this, "leftBrace");
		if ($this->rightBrace === null)
			throw ValidationException::missingChild($this, "rightBrace");
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

		foreach ($this->statements as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		foreach ($this->statements as $t)
			$t->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
