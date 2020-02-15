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

trait GeneratedStaticMethodCallExpression
{
	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $class;

	/**
	 * @var \Phi\Token|null
	 */
	private $operator;

	/**
	 * @var \Phi\Nodes\Helpers\MemberName|null
	 */
	private $name;

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
	 * @param \Phi\Nodes\Helpers\MemberName|\Phi\Node|string|null $name
	 */
	public function __construct($class = null, $name = null)
	{
		if ($class !== null)
		{
			$this->setClass($class);
		}
		if ($name !== null)
		{
			$this->setName($name);
		}
		$this->arguments = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Argument::class);
	}

	/**
	 * @param \Phi\Nodes\Expression $class
	 * @param \Phi\Token $operator
	 * @param \Phi\Nodes\Helpers\MemberName $name
	 * @param \Phi\Token $leftParenthesis
	 * @param mixed[] $arguments
	 * @param \Phi\Token $rightParenthesis
	 * @return self
	 */
	public static function __instantiateUnchecked($class, $operator, $name, $leftParenthesis, $arguments, $rightParenthesis)
	{
		$instance = new self;
	$instance->setClass($class);
	$instance->setOperator($operator);
	$instance->setName($name);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->arguments->__initUnchecked($arguments);
	$instance->arguments->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->class,
			$this->operator,
			$this->name,
			$this->leftParenthesis,
			$this->arguments,
			$this->rightParenthesis,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->class === $childToDetach)
			return $this->class;
		if ($this->operator === $childToDetach)
			return $this->operator;
		if ($this->name === $childToDetach)
			return $this->name;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->arguments === $childToDetach)
			return $this->arguments;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		throw new \LogicException();
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

	public function getOperator(): \Phi\Token
	{
		if ($this->operator === null)
		{
			throw TreeException::missingNode($this, "operator");
		}
		return $this->operator;
	}

	public function hasOperator(): bool
	{
		return $this->operator !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $operator
	 */
	public function setOperator($operator): void
	{
		if ($operator !== null)
		{
			/** @var \Phi\Token $operator */
			$operator = NodeCoercer::coerce($operator, \Phi\Token::class, $this->getPhpVersion());
			$operator->detach();
			$operator->parent = $this;
		}
		if ($this->operator !== null)
		{
			$this->operator->detach();
		}
		$this->operator = $operator;
	}

	public function getName(): \Phi\Nodes\Helpers\MemberName
	{
		if ($this->name === null)
		{
			throw TreeException::missingNode($this, "name");
		}
		return $this->name;
	}

	public function hasName(): bool
	{
		return $this->name !== null;
	}

	/**
	 * @param \Phi\Nodes\Helpers\MemberName|\Phi\Node|string|null $name
	 */
	public function setName($name): void
	{
		if ($name !== null)
		{
			/** @var \Phi\Nodes\Helpers\MemberName $name */
			$name = NodeCoercer::coerce($name, \Phi\Nodes\Helpers\MemberName::class, $this->getPhpVersion());
			$name->detach();
			$name->parent = $this;
		}
		if ($this->name !== null)
		{
			$this->name->detach();
		}
		$this->name = $name;
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

	/**
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Argument[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Argument>
	 */
	public function getArguments(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->arguments;
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

	public function _validate(int $flags): void
	{
		if ($this->class === null)
			throw ValidationException::missingChild($this, "class");
		if ($this->operator === null)
			throw ValidationException::missingChild($this, "operator");
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->operator->getType() !== 163)
			throw ValidationException::invalidSyntax($this->operator, [163]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->arguments->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

		if ($flags & 6)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		$this->class->_validate(1);
		$this->name->_validate(0);
		foreach ($this->arguments as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if ($this->class)
			$this->class->_autocorrect();
		if (!$this->operator)
			$this->setOperator(new Token(163, '::'));
		if ($this->name)
			$this->name->_autocorrect();
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->arguments as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));

		$this->extraAutocorrect();
	}
}
