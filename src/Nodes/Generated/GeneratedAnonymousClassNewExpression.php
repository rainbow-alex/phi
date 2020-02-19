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

trait GeneratedAnonymousClassNewExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $classKeyword;

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
	 * @var \Phi\Nodes\Oop\Extends_|null
	 */
	private $extends;

	/**
	 * @var \Phi\Nodes\Oop\Implements_|null
	 */
	private $implements;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftBrace;

	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\OopMember[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\OopMember>
	 */
	private $members;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightBrace;

	/**
	 */
	public function __construct()
	{
		$this->arguments = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Argument::class);
		$this->members = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Oop\OopMember::class);
	}

	/**
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $classKeyword
	 * @param \Phi\Token|null $leftParenthesis
	 * @param mixed[] $arguments
	 * @param \Phi\Token|null $rightParenthesis
	 * @param \Phi\Nodes\Oop\Extends_|null $extends
	 * @param \Phi\Nodes\Oop\Implements_|null $implements
	 * @param \Phi\Token $leftBrace
	 * @param mixed[] $members
	 * @param \Phi\Token $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($keyword, $classKeyword, $leftParenthesis, $arguments, $rightParenthesis, $extends, $implements, $leftBrace, $members, $rightBrace)
	{
		$instance = new self;
	$instance->setKeyword($keyword);
	$instance->setClassKeyword($classKeyword);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->arguments->__initUnchecked($arguments);
	$instance->arguments->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setExtends($extends);
	$instance->setImplements($implements);
	$instance->setLeftBrace($leftBrace);
	$instance->members->__initUnchecked($members);
	$instance->members->parent = $instance;
	$instance->setRightBrace($rightBrace);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->keyword,
			$this->classKeyword,
			$this->leftParenthesis,
			$this->arguments,
			$this->rightParenthesis,
			$this->extends,
			$this->implements,
			$this->leftBrace,
			$this->members,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->classKeyword === $childToDetach)
			return $this->classKeyword;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->arguments === $childToDetach)
			return $this->arguments;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		if ($this->extends === $childToDetach)
			return $this->extends;
		if ($this->implements === $childToDetach)
			return $this->implements;
		if ($this->leftBrace === $childToDetach)
			return $this->leftBrace;
		if ($this->members === $childToDetach)
			return $this->members;
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

	public function getClassKeyword(): \Phi\Token
	{
		if ($this->classKeyword === null)
		{
			throw TreeException::missingNode($this, "classKeyword");
		}
		return $this->classKeyword;
	}

	public function hasClassKeyword(): bool
	{
		return $this->classKeyword !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $classKeyword
	 */
	public function setClassKeyword($classKeyword): void
	{
		if ($classKeyword !== null)
		{
			/** @var \Phi\Token $classKeyword */
			$classKeyword = NodeCoercer::coerce($classKeyword, \Phi\Token::class, $this->getPhpVersion());
			$classKeyword->detach();
			$classKeyword->parent = $this;
		}
		if ($this->classKeyword !== null)
		{
			$this->classKeyword->detach();
		}
		$this->classKeyword = $classKeyword;
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

	public function getExtends(): ?\Phi\Nodes\Oop\Extends_
	{
		return $this->extends;
	}

	public function hasExtends(): bool
	{
		return $this->extends !== null;
	}

	/**
	 * @param \Phi\Nodes\Oop\Extends_|\Phi\Node|string|null $extends
	 */
	public function setExtends($extends): void
	{
		if ($extends !== null)
		{
			/** @var \Phi\Nodes\Oop\Extends_ $extends */
			$extends = NodeCoercer::coerce($extends, \Phi\Nodes\Oop\Extends_::class, $this->getPhpVersion());
			$extends->detach();
			$extends->parent = $this;
		}
		if ($this->extends !== null)
		{
			$this->extends->detach();
		}
		$this->extends = $extends;
	}

	public function getImplements(): ?\Phi\Nodes\Oop\Implements_
	{
		return $this->implements;
	}

	public function hasImplements(): bool
	{
		return $this->implements !== null;
	}

	/**
	 * @param \Phi\Nodes\Oop\Implements_|\Phi\Node|string|null $implements
	 */
	public function setImplements($implements): void
	{
		if ($implements !== null)
		{
			/** @var \Phi\Nodes\Oop\Implements_ $implements */
			$implements = NodeCoercer::coerce($implements, \Phi\Nodes\Oop\Implements_::class, $this->getPhpVersion());
			$implements->detach();
			$implements->parent = $this;
		}
		if ($this->implements !== null)
		{
			$this->implements->detach();
		}
		$this->implements = $implements;
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
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Nodes\Oop\OopMember[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Nodes\Oop\OopMember>
	 */
	public function getMembers(): \Phi\Nodes\Base\NodesList
	{
		return $this->members;
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
		if ($this->classKeyword === null)
			throw ValidationException::missingChild($this, "classKeyword");
		if ($this->leftBrace === null)
			throw ValidationException::missingChild($this, "leftBrace");
		if ($this->rightBrace === null)
			throw ValidationException::missingChild($this, "rightBrace");
		if ($this->keyword->getType() !== 219)
			throw ValidationException::invalidSyntax($this->keyword, [219]);
		if ($this->classKeyword->getType() !== 140)
			throw ValidationException::invalidSyntax($this->classKeyword, [140]);
		if ($this->leftParenthesis)
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->arguments->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis)
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		foreach ($this->arguments as $t)
			$t->_validate(0);
		if ($this->extends)
			$this->extends->_validate(0);
		if ($this->implements)
			$this->implements->_validate(0);
		foreach ($this->members as $t)
			$t->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->keyword)
			$this->setKeyword(new Token(219, 'new'));
		if (!$this->classKeyword)
			$this->setClassKeyword(new Token(140, 'class'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->arguments as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if ($this->extends)
			$this->extends->_autocorrect();
		if ($this->implements)
			$this->implements->_autocorrect();
		if (!$this->leftBrace)
			$this->setLeftBrace(new Token(124, '{'));
		foreach ($this->members as $t)
			$t->_autocorrect();
		if (!$this->rightBrace)
			$this->setRightBrace(new Token(126, '}'));

		$this->extraAutocorrect();
	}
}
