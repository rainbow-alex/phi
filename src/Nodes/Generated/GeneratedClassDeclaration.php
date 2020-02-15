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

trait GeneratedClassDeclaration
{
	/**
	 * @var \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-var \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	private $modifiers;

	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $name;

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
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 */
	public function __construct($name = null)
	{
		$this->modifiers = new \Phi\Nodes\Base\NodesList(\Phi\Token::class);
		if ($name !== null)
		{
			$this->setName($name);
		}
		$this->members = new \Phi\Nodes\Base\NodesList(\Phi\Nodes\Oop\OopMember::class);
	}

	/**
	 * @param mixed[] $modifiers
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token $name
	 * @param \Phi\Nodes\Oop\Extends_|null $extends
	 * @param \Phi\Nodes\Oop\Implements_|null $implements
	 * @param \Phi\Token $leftBrace
	 * @param mixed[] $members
	 * @param \Phi\Token $rightBrace
	 * @return self
	 */
	public static function __instantiateUnchecked($modifiers, $keyword, $name, $extends, $implements, $leftBrace, $members, $rightBrace)
	{
		$instance = new self;
	$instance->modifiers->__initUnchecked($modifiers);
	$instance->modifiers->parent = $instance;
	$instance->setKeyword($keyword);
	$instance->setName($name);
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
			$this->modifiers,
			$this->keyword,
			$this->name,
			$this->extends,
			$this->implements,
			$this->leftBrace,
			$this->members,
			$this->rightBrace,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->modifiers === $childToDetach)
			return $this->modifiers;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->name === $childToDetach)
			return $this->name;
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

	/**
	 * @return \Phi\Nodes\Base\NodesList|\Phi\Token[]
	 * @phpstan-return \Phi\Nodes\Base\NodesList<\Phi\Token>
	 */
	public function getModifiers(): \Phi\Nodes\Base\NodesList
	{
		return $this->modifiers;
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

	public function getName(): \Phi\Token
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
	 * @param \Phi\Token|\Phi\Node|string|null $name
	 */
	public function setName($name): void
	{
		if ($name !== null)
		{
			/** @var \Phi\Token $name */
			$name = NodeCoercer::coerce($name, \Phi\Token::class, $this->getPhpVersion());
			$name->detach();
			$name->parent = $this;
		}
		if ($this->name !== null)
		{
			$this->name->detach();
		}
		$this->name = $name;
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
		if ($this->name === null)
			throw ValidationException::missingChild($this, "name");
		if ($this->leftBrace === null)
			throw ValidationException::missingChild($this, "leftBrace");
		if ($this->rightBrace === null)
			throw ValidationException::missingChild($this, "rightBrace");
		foreach ($this->modifiers as $t)
			if (!\in_array($t->getType(), [128, 181], true))
				throw ValidationException::invalidSyntax($t, [128, 181]);
		if ($this->keyword->getType() !== 140)
			throw ValidationException::invalidSyntax($this->keyword, [140]);
		if ($this->name->getType() !== 245)
			throw ValidationException::invalidSyntax($this->name, [245]);
		if ($this->leftBrace->getType() !== 124)
			throw ValidationException::invalidSyntax($this->leftBrace, [124]);
		if ($this->rightBrace->getType() !== 126)
			throw ValidationException::invalidSyntax($this->rightBrace, [126]);


		$this->extraValidation($flags);

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
			$this->setKeyword(new Token(140, 'class'));
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
