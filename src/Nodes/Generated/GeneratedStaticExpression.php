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

trait GeneratedStaticExpression
{
	/**
	 * @var \Phi\Token|null
	 */
	private $token;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $token
	 */
	public function __construct($token = null)
	{
		if ($token !== null)
		{
			$this->setToken($token);
		}
	}

	/**
	 * @param \Phi\Token $token
	 * @return self
	 */
	public static function __instantiateUnchecked($token)
	{
		$instance = new self;
	$instance->setToken($token);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->token,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->token === $childToDetach)
			return $this->token;
		throw new \LogicException();
	}

	public function getToken(): \Phi\Token
	{
		if ($this->token === null)
		{
			throw TreeException::missingNode($this, "token");
		}
		return $this->token;
	}

	public function hasToken(): bool
	{
		return $this->token !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $token
	 */
	public function setToken($token): void
	{
		if ($token !== null)
		{
			/** @var \Phi\Token $token */
			$token = NodeCoercer::coerce($token, \Phi\Token::class, $this->getPhpVersion());
			$token->detach();
			$token->parent = $this;
		}
		if ($this->token !== null)
		{
			$this->token->detach();
		}
		$this->token = $token;
	}

	public function _validate(int $flags): void
	{
		if ($this->token === null)
			throw ValidationException::missingChild($this, "token");
		if ($this->token->getType() !== 244)
			throw ValidationException::invalidSyntax($this->token, [244]);

		if ($flags & 14)
			throw ValidationException::invalidExpressionInContext($this);

		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{
		if (!$this->token)
			$this->setToken(new Token(244, 'static'));

		$this->extraAutocorrect();
	}
}
