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

trait GeneratedConstantInterpolatedStringPart
{
	/**
	 * @var \Phi\Token|null
	 */
	private $content;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $content
	 */
	public function __construct($content = null)
	{
		if ($content !== null)
		{
			$this->setContent($content);
		}
	}

	/**
	 * @param \Phi\Token $content
	 * @return self
	 */
	public static function __instantiateUnchecked($content)
	{
		$instance = new self;
	$instance->setContent($content);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->content,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->content === $childToDetach)
			return $this->content;
		throw new \LogicException();
	}

	public function getContent(): \Phi\Token
	{
		if ($this->content === null)
		{
			throw TreeException::missingNode($this, "content");
		}
		return $this->content;
	}

	public function hasContent(): bool
	{
		return $this->content !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $content
	 */
	public function setContent($content): void
	{
		if ($content !== null)
		{
			/** @var \Phi\Token $content */
			$content = NodeCoercer::coerce($content, \Phi\Token::class, $this->getPhpVersion());
			$content->detach();
			$content->parent = $this;
		}
		if ($this->content !== null)
		{
			$this->content->detach();
		}
		$this->content = $content;
	}

	public function _validate(int $flags): void
	{
		if ($this->content === null)
			throw ValidationException::missingChild($this, "content");
		if ($this->content->getType() !== 169)
			throw ValidationException::invalidSyntax($this->content, [169]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{

		$this->extraAutocorrect();
	}
}
