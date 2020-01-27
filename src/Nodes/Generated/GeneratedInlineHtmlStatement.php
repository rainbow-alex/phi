<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Exception\ValidationException;
use Phi\Nodes as Nodes;

abstract class GeneratedInlineHtmlStatement extends Nodes\Statement
{
    /**
     * @var Token|null
     */
    private $closingTag;

    /**
     * @var Token|null
     */
    private $content;

    /**
     * @var Token|null
     */
    private $openingTag;

    /**
     * @param Token|Node|string|null $closingTag
     * @param Token|Node|string|null $content
     * @param Token|Node|string|null $openingTag
     */
    public function __construct($closingTag = null, $content = null, $openingTag = null)
    {
        if ($closingTag !== null)
        {
            $this->setClosingTag($closingTag);
        }
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
     * @param int $phpVersion
     * @param Token|null $closingTag
     * @param Token|null $content
     * @param Token|null $openingTag
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $closingTag, $content, $openingTag)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->closingTag = $closingTag;
        if ($closingTag)
        {
            $instance->closingTag->parent = $instance;
        }
        $instance->content = $content;
        if ($content)
        {
            $instance->content->parent = $instance;
        }
        $instance->openingTag = $openingTag;
        if ($openingTag)
        {
            $instance->openingTag->parent = $instance;
        }
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'closingTag' => &$this->closingTag,
            'content' => &$this->content,
            'openingTag' => &$this->openingTag,
        ];
        return $refs;
    }

    public function getClosingTag(): ?Token
    {
        return $this->closingTag;
    }

    public function hasClosingTag(): bool
    {
        return $this->closingTag !== null;
    }

    /**
     * @param Token|Node|string|null $closingTag
     */
    public function setClosingTag($closingTag): void
    {
        if ($closingTag !== null)
        {
            /** @var Token $closingTag */
            $closingTag = NodeConverter::convert($closingTag, Token::class, $this->phpVersion);
            $closingTag->detach();
            $closingTag->parent = $this;
        }
        if ($this->closingTag !== null)
        {
            $this->closingTag->detach();
        }
        $this->closingTag = $closingTag;
    }

    public function getContent(): ?Token
    {
        return $this->content;
    }

    public function hasContent(): bool
    {
        return $this->content !== null;
    }

    /**
     * @param Token|Node|string|null $content
     */
    public function setContent($content): void
    {
        if ($content !== null)
        {
            /** @var Token $content */
            $content = NodeConverter::convert($content, Token::class, $this->phpVersion);
            $content->detach();
            $content->parent = $this;
        }
        if ($this->content !== null)
        {
            $this->content->detach();
        }
        $this->content = $content;
    }

    public function getOpeningTag(): ?Token
    {
        return $this->openingTag;
    }

    public function hasOpeningTag(): bool
    {
        return $this->openingTag !== null;
    }

    /**
     * @param Token|Node|string|null $openingTag
     */
    public function setOpeningTag($openingTag): void
    {
        if ($openingTag !== null)
        {
            /** @var Token $openingTag */
            $openingTag = NodeConverter::convert($openingTag, Token::class, $this->phpVersion);
            $openingTag->detach();
            $openingTag->parent = $this;
        }
        if ($this->openingTag !== null)
        {
            $this->openingTag->detach();
        }
        $this->openingTag = $openingTag;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
