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

trait GeneratedSwitchStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftParenthesis;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $value;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\SwitchCase[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\SwitchCase>
	 */
	private $cases;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 */
	public function __construct()
	{
		$this->cases = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Statements\SwitchCase::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $leftParenthesis
	 * @param \Phi\Nodes\Expression $value
	 * @param \Phi\Token $rightParenthesis
	 * @param \Phi\Token $leftBrace
	 * @param mixed[] $cases
	 * @param \Phi\Token $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $leftParenthesis, $value, $rightParenthesis, $leftBrace, $cases, $rightBrace)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->setValue($value);
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setLeftBrace($leftBrace);
	$instance->cases->__initUnchecked($cases);
	$instance->cases->parent = $instance;
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->leftParenthesis,
			$this->value,
			$this->rightParenthesis,
			$this->leftBrace,
			$this->cases,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->value === $childToDetach)
			return $this->value;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->cases === $childToDetach)
			return $this->cases;
		if ($this->rightBrace === $childToDetach)
			return $this->rightBrace;
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

	public function getLeftParenthesis(): \Phi\Token
	{
		if ($this->leftParenthesis === null)
		{
			throw TreeException::missingNode($this, "leftParenthesis");
		}
		return $this->leftParenthesis;
	}

	public function hasLeftParenthesis(): bool
	{
		return $this->leftParenthesis !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $leftParenthesis
	 */
	public function setLeftParenthesis($leftParenthesis): void
	{
		if ($leftParenthesis !== null)
		{
			/** @var \Phi\Token $leftParenthesis */
			$leftParenthesis = NodeCoercer::coerce($leftParenthesis, \Phi\Token::class, $this->getPhpVersion());
			$leftParenthesis->detach();
			$leftParenthesis->parent = $this;
		}
		if ($this->leftParenthesis !== null)
		{
			$this->leftParenthesis->detach();
		}
		$this->leftParenthesis = $leftParenthesis;
	}

	public function getValue(): \Phi\Nodes\Expression
	{
		if ($this->value === null)
		{
			throw TreeException::missingNode($this, "value");
		}
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

	public function getRightParenthesis(): \Phi\Token
	{
		if ($this->rightParenthesis === null)
		{
			throw TreeException::missingNode($this, "rightParenthesis");
		}
		return $this->rightParenthesis;
	}

	public function hasRightParenthesis(): bool
	{
		return $this->rightParenthesis !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $rightParenthesis
	 */
	public function setRightParenthesis($rightParenthesis): void
	{
		if ($rightParenthesis !== null)
		{
			/** @var \Phi\Token $rightParenthesis */
			$rightParenthesis = NodeCoercer::coerce($rightParenthesis, \Phi\Token::class, $this->getPhpVersion());
			$rightParenthesis->detach();
			$rightParenthesis->parent = $this;
		}
		if ($this->rightParenthesis !== null)
		{
			$this->rightParenthesis->detach();
		}
		$this->rightParenthesis = $rightParenthesis;
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
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Statements\SwitchCase[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Statements\SwitchCase>
	 */
	public function getCases(): \Phi\Nodes\Base\NodesList
	{
		return $this->cases;
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
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->value === null)
			throw ValidationException::missingChild($this, "value");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->leftBrace === null)
			throw ValidationException::missingChild($this, "leftBrace");
		if ($this->rightBrace === null)
			throw ValidationException::missingChild($this, "rightBrace");
		if ($this->keyword->getType() !== 248)
			throw ValidationException::invalidSyntax($this->keyword, [248]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

		$this->value->_validate(1);
		foreach ($this->cases as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(248, 'switch'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		if ($this->value)
			$this->value->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		foreach ($this->cases as $t)
			$t->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
