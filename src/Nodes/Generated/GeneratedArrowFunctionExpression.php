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

trait GeneratedArrowFunctionExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $staticKeyword;

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
	 * @var \Phi\Nodes\Helpers\ReturnType|null
	 */
	private $returnType;

	/**
	 * @var \Phi\Token|null
	 */
	private $arrow;

	/**
	 * @var \Phi\Nodes\Expression|null
	 */
	private $body;

	/**
	 */
	public function __construct()
	{
		$this->parameters = new \Phi\Nodes\Base\SeparatedNodesList(\Phi\Nodes\Helpers\Parameter::class);
	}

	/**
	 * @param \Phi\Token|null $staticKeyword
	 * @param \Phi\Token $keyword
	 * @param \Phi\Token|null $byReference
	 * @param \Phi\Token $leftParenthesis
	 * @param mixed[] $parameters
	 * @param \Phi\Token $rightParenthesis
	 * @param \Phi\Nodes\Helpers\ReturnType|null $returnType
	 * @param \Phi\Token $arrow
	 * @param \Phi\Nodes\Expression $body
	 * @return self
	 */
	public static function __instantiateUnchecked($staticKeyword, $keyword, $byReference, $leftParenthesis, $parameters, $rightParenthesis, $returnType, $arrow, $body)
	{
		$instance = new self;
	$instance->setStaticKeyword($staticKeyword);
	$instance->setKeyword($keyword);
	$instance->setByReference($byReference);
	$instance->setLeftParenthesis($leftParenthesis);
	$instance->parameters->__initUnchecked($parameters);
	$instance->parameters->parent = $instance;
	$instance->setRightParenthesis($rightParenthesis);
	$instance->setReturnType($returnType);
	$instance->setArrow($arrow);
	$instance->setBody($body);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->staticKeyword,
			$this->keyword,
			$this->byReference,
			$this->leftParenthesis,
			$this->parameters,
			$this->rightParenthesis,
			$this->returnType,
			$this->arrow,
			$this->body,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->staticKeyword === $childToDetach)
			return $this->staticKeyword;
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
		if ($this->returnType === $childToDetach)
			return $this->returnType;
		if ($this->arrow === $childToDetach)
			return $this->arrow;
		if ($this->body === $childToDetach)
			return $this->body;
		throw new \LogicException();
	}

	public function getStaticKeyword(): ?\Phi\Token
	{
		return $this->staticKeyword;
	}

	public function hasStaticKeyword(): bool
	{
		return $this->staticKeyword !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $staticKeyword
	 */
	public function setStaticKeyword($staticKeyword): void
	{
		if ($staticKeyword !== null)
		{
			/** @var \Phi\Token $staticKeyword */
			$staticKeyword = NodeCoercer::coerce($staticKeyword, \Phi\Token::class, $this->getPhpVersion());
			$staticKeyword->detach();
			$staticKeyword->parent = $this;
		}
		if ($this->staticKeyword !== null)
		{
			$this->staticKeyword->detach();
		}
		$this->staticKeyword = $staticKeyword;
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

	public function getArrow(): \Phi\Token
	{
		if ($this->arrow === null)
		{
			throw TreeException::missingNode($this, "arrow");
		}
		return $this->arrow;
	}

	public function hasArrow(): bool
	{
		return $this->arrow !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $arrow
	 */
	public function setArrow($arrow): void
	{
		if ($arrow !== null)
		{
			/** @var \Phi\Token $arrow */
			$arrow = NodeCoercer::coerce($arrow, \Phi\Token::class, $this->getPhpVersion());
			$arrow->detach();
			$arrow->parent = $this;
		}
		if ($this->arrow !== null)
		{
			$this->arrow->detach();
		}
		$this->arrow = $arrow;
	}

	public function getBody(): \Phi\Nodes\Expression
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
	 * @param \Phi\Nodes\Expression|\Phi\Node|string|null $body
	 */
	public function setBody($body): void
	{
		if ($body !== null)
		{
			/** @var \Phi\Nodes\Expression $body */
			$body = NodeCoercer::coerce($body, \Phi\Nodes\Expression::class, $this->getPhpVersion());
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
		if ($this->arrow === null)
			throw ValidationException::missingChild($this, "arrow");
		if ($this->body === null)
			throw ValidationException::missingChild($this, "body");
		if ($this->staticKeyword)
		if ($this->staticKeyword->getType() !== 244)
			throw ValidationException::invalidSyntax($this->staticKeyword, [244]);
		if ($this->keyword->getType() !== 183)
			throw ValidationException::invalidSyntax($this->keyword, [183]);
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
		if ($this->arrow->getType() !== 161)
			throw ValidationException::invalidSyntax($this->arrow, [161]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

		foreach ($this->parameters as $t)
			$t->_validate(0);
		if ($this->returnType)
			$this->returnType->_validate(0);
		$this->body->_validate(1);
	}

	public function _autocorrect(): void
	{
		if (!$this->staticKeyword)
			$this->setStaticKeyword(new Token(244, 'static'));
		if (!$this->keyword)
			$this->setKeyword(new Token(183, 'fn'));
		if (!$this->byReference)
			$this->setByReference(new Token(104, '&'));
		if (!$this->leftParenthesis)
			$this->setLeftParenthesis(new Token(105, '('));
		foreach ($this->parameters as $t)
			$t->_autocorrect();
		if (!$this->rightParenthesis)
			$this->setRightParenthesis(new Token(106, ')'));
		if ($this->returnType)
			$this->returnType->_autocorrect();
		if (!$this->arrow)
			$this->setArrow(new Token(161, '=>'));
		if ($this->body)
			$this->body->_autocorrect();

		$this->extraAutocorrect();
	}
}
