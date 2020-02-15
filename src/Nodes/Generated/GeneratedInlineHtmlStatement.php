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

trait GeneratedInlineHtmlStatement
{
	/**
	 * @var \Phi\Token|null
	 */
	private $content;

	/**
	 * @var \Phi\Token|null
	 */
	private $openingTag;

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $content
	 * @param \Phi\Token|\Phi\Node|string|null $openingTag
	 */
	public function __construct($content = null, $openingTag = null)
	{
		if ($content !== null)
		{
			$this->setContent($content);
		}
		if ($openingTag !== null)
		{
			$this->setOpeningTag($openingTag);
		}
	}

	/**
	 * @param \Phi\Token|null $content
	 * @param \Phi\Token|null $openingTag
	 * @return self
	 */
	public static function __instantiateUnchecked($content, $openingTag)
	{
		$instance = new self;
	$instance->setContent($content);
	$instance->setOpeningTag($openingTag);
		return $instance;
	}

	public function getChildNodes(): array
	{
		return \array_values(\array_filter([
			$this->content,
			$this->openingTag,
		]));
	}

	protected function &getChildRef(Node $childToDetach): Node
	{
		if ($this->content === $childToDetach)
			return $this->content;
		if ($this->openingTag === $childToDetach)
			return $this->openingTag;
		throw new \LogicException();
	}

	public function getContent(): ?\Phi\Token
	{
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

	public function getOpeningTag(): ?\Phi\Token
	{
		return $this->openingTag;
	}

	public function hasOpeningTag(): bool
	{
		return $this->openingTag !== null;
	}

	/**
	 * @param \Phi\Token|\Phi\Node|string|null $openingTag
	 */
	public function setOpeningTag($openingTag): void
	{
		if ($openingTag !== null)
		{
			/** @var \Phi\Token $openingTag */
			$openingTag = NodeCoercer::coerce($openingTag, \Phi\Token::class, $this->getPhpVersion());
			$openingTag->detach();
			$openingTag->parent = $this;
		}
		if ($this->openingTag !== null)
		{
			$this->openingTag->detach();
		}
		$this->openingTag = $openingTag;
	}

	public function _validate(int $flags): void
	{
		if ($this->content)
		if ($this->content->getType() !== 196)
			throw ValidationException::invalidSyntax($this->content, [196]);
		if ($this->openingTag)
		if ($this->openingTag->getType() !== 225)
			throw ValidationException::invalidSyntax($this->openingTag, [225]);


		$this->extraValidation($flags);

	}

	public function _autocorrect(): void
	{
		if (!$this->openingTag)
			$this->setOpeningTag(new Token(225, '<?php '));

		$this->extraAutocorrect();
	}
}
