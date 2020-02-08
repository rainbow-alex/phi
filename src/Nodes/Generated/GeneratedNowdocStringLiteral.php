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

trait GeneratedNowdocStringLiteral
{
    /**
     * @var \Phi\Token|null
     */
    private $leftDelimiter;

    /**
     * @var \Phi\Token|null
     */
    private $content;

    /**
     * @var \Phi\Token|null
     */
    private $rightDelimiter;

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
     * @param \Phi\Token $leftDelimiter
     * @param \Phi\Token|null $content
     * @param \Phi\Token $rightDelimiter
     * @return self
     */
    public static function __instantiateUnchecked($leftDelimiter, $content, $rightDelimiter)
    {
        $instance = new self;
        $instance->leftDelimiter = $leftDelimiter;
        $leftDelimiter->parent = $instance;
        $instance->content = $content;
        if ($content) $content->parent = $instance;
        $instance->rightDelimiter = $rightDelimiter;
        $rightDelimiter->parent = $instance;
        return $instance;
    }

    public function getChildNodes(): array
    {
        return \array_values(\array_filter([
            $this->leftDelimiter,
            $this->content,
            $this->rightDelimiter,
        ]));
    }

    protected function &getChildRef(Node $childToDetach): Node
    {
        if ($this->leftDelimiter === $childToDetach)
        {
            return $this->leftDelimiter;
        }
        if ($this->content === $childToDetach)
        {
            return $this->content;
        }
        if ($this->rightDelimiter === $childToDetach)
        {
            return $this->rightDelimiter;
        }
        throw new \LogicException();
    }

    public function getLeftDelimiter(): \Phi\Token
    {
        if ($this->leftDelimiter === null)
        {
            throw TreeException::missingNode($this, "leftDelimiter");
        }
        return $this->leftDelimiter;
    }

    public function hasLeftDelimiter(): bool
    {
        return $this->leftDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $leftDelimiter
     */
    public function setLeftDelimiter($leftDelimiter): void
    {
        if ($leftDelimiter !== null)
        {
            /** @var \Phi\Token $leftDelimiter */
            $leftDelimiter = NodeCoercer::coerce($leftDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $leftDelimiter->detach();
            $leftDelimiter->parent = $this;
        }
        if ($this->leftDelimiter !== null)
        {
            $this->leftDelimiter->detach();
        }
        $this->leftDelimiter = $leftDelimiter;
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

    public function getRightDelimiter(): \Phi\Token
    {
        if ($this->rightDelimiter === null)
        {
            throw TreeException::missingNode($this, "rightDelimiter");
        }
        return $this->rightDelimiter;
    }

    public function hasRightDelimiter(): bool
    {
        return $this->rightDelimiter !== null;
    }

    /**
     * @param \Phi\Token|\Phi\Node|string|null $rightDelimiter
     */
    public function setRightDelimiter($rightDelimiter): void
    {
        if ($rightDelimiter !== null)
        {
            /** @var \Phi\Token $rightDelimiter */
            $rightDelimiter = NodeCoercer::coerce($rightDelimiter, \Phi\Token::class, $this->getPhpVersion());
            $rightDelimiter->detach();
            $rightDelimiter->parent = $this;
        }
        if ($this->rightDelimiter !== null)
        {
            $this->rightDelimiter->detach();
        }
        $this->rightDelimiter = $rightDelimiter;
    }

    public function _validate(int $flags): void
    {
        if ($this->leftDelimiter === null)
            throw ValidationException::missingChild($this, "leftDelimiter");
        if ($this->rightDelimiter === null)
            throw ValidationException::missingChild($this, "rightDelimiter");
        if ($this->leftDelimiter->getType() !== 241)
            throw ValidationException::invalidSyntax($this->leftDelimiter, [241]);
        if ($this->content)
        if ($this->content->getType() !== 168)
            throw ValidationException::invalidSyntax($this->content, [168]);
        if ($this->rightDelimiter->getType() !== 175)
            throw ValidationException::invalidSyntax($this->rightDelimiter, [175]);

        if ($flags & 14)
            throw ValidationException::invalidExpressionInContext($this);

        $this->extraValidation($flags);

    }

    public function _autocorrect(): void
    {

        $this->extraAutocorrect();
    }
}
