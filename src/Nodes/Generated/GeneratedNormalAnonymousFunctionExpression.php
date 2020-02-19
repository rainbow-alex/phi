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

trait GeneratedNormalAnonymousFunctionExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $staticModifier;

	/**
	 * @var \Phi\Token|null
	 */
	private $keyword;

	/**
	 * @var \Phi\Token|null
	 */
	private $byReference;

	/**
	 * @var \Phi\Token|null
	 */
	private $leftParenthesis;

	/**
	 * @var \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Parameter[]
	 * @phpstan-var \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Parameter>
	 */
	private $parameters;

	/**
	 * @var \Phi\Token|null
	 */
	private $rightParenthesis;

	/**
	 * @var \Phi\Nodes\Expressions\AnonymousFunctionUse|null
	 */
	private $use;

	/**
	 * @var \Phi\Nodes\Helpers\ReturnType|null
	 */
	private $returnType;

	/**
	 * @var \Phi\Nodes\Blocks\RegularBlock|null
	 */
	private $body;

	/**
	 */
	public function __construct()
	{
		$this->parameters = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Parameter::class);
	}

	/**
	 * @param \Phi\Token|null $staticModifier
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token|null $byReference
	 * @param \Phi\Token $leftParenthesis
	 * @param mixed[] $parameters
	 * @param \Phi\Token $rightParenthesis
	 * @param \Phi\Nodes\Expressions\AnonymousFunctionUse|null $use
	 * @param \Phi\Nodes\Helpers\ReturnType|null $returnType
	 * @param \Phi\Nodes\Blocks\RegularBlock $body
	 * @return self
	 */
	public static function __instantiateUnchecked($staticModifier, $keyword, $byReference, $leftParenthesis, $parameters, $rightParenthesis, $use, $returnType, $body)
	{
		$instance = new self;
	$instance->setStaticModifier($staticModifier);
	$instance->setKeyword($keyword);
	$instance->setByReference($byReference);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->parameters->__initUnchecked($parameters);
	$instance->parameters->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setUse($use);
	$instance->setReturnType($returnType);
	$instance->setBody($body);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->staticModifier,
			$this->keyword,
			$this->byReference,
			$this->leftParenthesis,
			$this->parameters,
			$this->rightParenthesis,
			$this->use,
			$this->returnType,
			$this->body,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->staticModifier === $childToDetach)
			return $this->staticModifier;
		if ($this->keyword === $childToDetach)
			return $this->keyword;
		if ($this->byReference === $childToDetach)
			return $this->byReference;
		if ($this->leftParenthesis === $childToDetach)
			return $this->leftParenthesis;
		if ($this->parameters === $childToDetach)
			return $this->parameters;
		if ($this->rightParenthesis === $childToDetach)
			return $this->rightParenthesis;
		if ($this->use === $childToDetach)
			return $this->use;
		if ($this->returnType === $childToDetach)
			return $this->returnType;
		if ($this->body === $childToDetach)
			return $this->body;
		throw new \LogicException();
	}

	public function getStaticModifier(): ?\Phi\Token
	{
		return $this->staticModifier;
	}

	public function hasStaticModifier(): bool
	{
		return $this->staticModifier !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $staticModifier
	 */
	public function setStaticModifier($staticModifier): void
	{
		if ($staticModifier !== null)
		{
			/** @var \Phi\Token $staticModifier */
			$staticModifier = NodeCoercer::coerce($staticModifier, \Phi\Token::class, $this->getPhpVersion());
			$staticModifier->detach();
			$staticModifier->parent = $this;
		}
		if ($this->staticModifier !== null)
		{
			$this->staticModifier->detach();
		}
		$this->staticModifier = $staticModifier;
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

	public function getByReference(): ?\Phi\Token
	{
		return $this->byReference;
	}

	public function hasByReference(): bool
	{
		return $this->byReference !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $byReference
	 */
	public function setByReference($byReference): void
	{
		if ($byReference !== null)
		{
			/** @var \Phi\Token $byReference */
			$byReference = NodeCoercer::coerce($byReference, \Phi\Token::class, $this->getPhpVersion());
			$byReference->detach();
			$byReference->parent = $this;
		}
		if ($this->byReference !== null)
		{
			$this->byReference->detach();
		}
		$this->byReference = $byReference;
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
	 * @return \Phi\Nodes\Base\SeparatedNodesList|\Phi\Nodes\Helpers\Parameter[]
	 * @phpstan-return \Phi\Nodes\Base\SeparatedNodesList<\Phi\Nodes\Helpers\Parameter>
	 */
	public function getParameters(): \Phi\Nodes\Base\SeparatedNodesList
	{
		return $this->parameters;
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

	public function getUse(): ?\Phi\Nodes\Expressions\AnonymousFunctionUse
	{
		return $this->use;
	}

	public function hasUse(): bool
	{
		return $this->use !== null;
	}

	/**
	 * @param \Phi\Nodes\Expressions\AnonymousFunctionUse|\Phi\Node|string|null $use
	 */
	public function setUse($use): void
	{
		if ($use !== null)
		{
			/** @var \Phi\Nodes\Expressions\AnonymousFunctionUse $use */
			$use = NodeCoercer::coerce($use, \Phi\Nodes\Expressions\AnonymousFunctionUse::class, $this->getPhpVersion());
			$use->detach();
			$use->parent = $this;
		}
		if ($this->use !== null)
		{
			$this->use->detach();
		}
		$this->use = $use;
	}

	public function getReturnType(): ?\Phi\Nodes\Helpers\ReturnType
	{
		return $this->returnType;
	}

	public function hasReturnType(): bool
	{
		return $this->returnType !== null;
	}

	/**
	 * @param \Phi\Nodes\Helpers\ReturnType|\Phi\Node|string|null $returnType
	 */
	public function setReturnType($returnType): void
	{
		if ($returnType !== null)
		{
			/** @var \Phi\Nodes\Helpers\ReturnType $returnType */
			$returnType = NodeCoercer::coerce($returnType, \Phi\Nodes\Helpers\ReturnType::class, $this->getPhpVersion());
			$returnType->detach();
			$returnType->parent = $this;
		}
		if ($this->returnType !== null)
		{
			$this->returnType->detach();
		}
		$this->returnType = $returnType;
	}

	public function getBody(): \Phi\Nodes\Blocks\RegularBlock
	{
		if ($this->body === null)
		{
			throw TreeException::missingNode($this, "body");
		}
		return $this->body;
	}

	public function hasBody(): bool
	{
		return $this->body !== null;
	}

	/**
	 * @param \Phi\Nodes\Blocks\RegularBlock|\Phi\Node|string|null $body
	 */
	public function setBody($body): void
	{
		if ($body !== null)
		{
			/** @var \Phi\Nodes\Blocks\RegularBlock $body */
			$body = NodeCoercer::coerce($body, \Phi\Nodes\Blocks\RegularBlock::class, $this->getPhpVersion());
			$body->detach();
			$body->parent = $this;
		}
		if ($this->body !== null)
		{
			$this->body->detach();
		}
		$this->body = $body;
	}

	public function _validate(int $flags): void
	{
		if ($this->keyword === null)
			throw ValidationException::missingChild($this, "keyword");
		if ($this->leftParenthesis === null)
			throw ValidationException::missingChild($this, "leftParenthesis");
		if ($this->rightParenthesis === null)
			throw ValidationException::missingChild($this, "rightParenthesis");
		if ($this->body === null)
			throw ValidationException::missingChild($this, "body");
		if ($this->staticModifier)
		if ($this->staticModifier->getType() !== 244)
			throw ValidationException::invalidSyntax($this->staticModifier, [244]);
		if ($this->keyword->getType() !== 186)
			throw ValidationException::invalidSyntax($this->keyword, [186]);
		if ($this->byReference)
		if ($this->byReference->getType() !== 104)
			throw ValidationException::invalidSyntax($this->byReference, [104]);
		if ($this->leftParenthesis->getType() !== 105)
			throw ValidationException::invalidSyntax($this->leftParenthesis, [105]);
		foreach ($this->parameters->getSeparators() as $t)
			if ($t && $t->getType() !== 109)
				throw ValidationException::invalidSyntax($t, [109]);
		if ($this->rightParenthesis->getType() !== 106)
			throw ValidationException::invalidSyntax($this->rightParenthesis, [106]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		foreach ($this->parameters as $t)
			$t->_validate(0);
		if ($this->use)
			$this->use->_validate(0);
		if ($this->returnType)
			$this->returnType->_validate(0);
		$this->body->_validate(0);
	}

	public function _autocorrect(): void
	{
		if (!$this->staticModifier)
			$this->setStaticModifier(new Token(244, 'static'));
		if (!$this->keyword)
			$this->setKeyword(new Token(186, 'function'));
		if (!$this->byReference)
			$this->setByReference(new Token(104, '&'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->parameters as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if ($this->use)
			$this->use->_autocorrect();
		if ($this->returnType)
			$this->returnType->_autocorrect();
		if ($this->body)
			$this->body->_autocorrect();

		$this->extraAutocorrect();
	}
}
