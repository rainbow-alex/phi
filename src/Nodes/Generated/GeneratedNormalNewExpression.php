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

trait GeneratedNormalNewExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $class;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftParenthesis;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Argument[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Argument>
	 */
	private $arguments;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
	 */
	public function __construct($class = null)
	{
		if ($class !== null)
		{
			$this->setClass($class);
		}
		$this->arguments = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Argument::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Nodes\Expression $class
	 * @param \Phi\Token|null $leftParenthesis
	 * @param mixed[] $arguments
	 * @param \Phi\Token|null $rightParenthesis
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $class, $leftParenthesis, $arguments, $rightParenthesis)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setClass($class);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->arguments->__initUnchecked($arguments);
	$instance->arguments->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->class,
			$this->leftParenthesis,
			$this->arguments,
			$this->rightParenthesis,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->class === $childToDetach)
			return $this->class;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->arguments === $childToDetach)
			return $this->arguments;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
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

	public function getClass(): \Phi\Nodes\Expression
	{
		if ($this->class === null)
		{
			throw TreeException::missingNode($this, "class");
		}
		return $this->class;
	}

	public function hasClass(): bool
	{
		return $this->class !== null;
	}

	/**
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $class
	 */
	public function setClass($class): void
	{
		if ($class !== null)
		{
			/** @var \Phi\Nodes\Expression $class */
			$class = NodeCoercer::coerce($class, \Phi\Nodes\Expression::class, $this->getPhpVersion());
			$class->detach();
			$class->parent = $this;
		}
		if ($this->class !== null)
		{
			$this->class->detach();
		}
		$this->class = $class;
	}

	public function getLeftParenthesis(): ?\Phi\Token
	{
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Argument[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Argument>
	 */
	public function getArguments(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->arguments;
	}

	public function getRightParenthesis(): ?\Phi\Token
	{
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

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->class === null)
			throw ValidationException::missingChild($this, "class");
		if ($this->keyword->getType() !== 219)
			throw ValidationException::invalidSyntax($this->keyword, [219]);
		if ($this->leftParenthesis)
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->arguments->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis)
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->class->_validate(1);
		foreach ($this->arguments as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(219, 'new'));
		if ($this->class)
			$this->class->_autocorrect();
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->arguments as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));

		$this->extraAutocorrect();
	}
}
