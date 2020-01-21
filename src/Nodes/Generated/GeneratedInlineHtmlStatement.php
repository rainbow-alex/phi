<?php

namespace Phi\Nodes\Generated;

use Phi\Node;
use Phi\Token;
use Phi\Nodes\Base\CompoundNode;
use Phi\Nodes\Base\NodesList;
use Phi\Nodes\Base\SeparatedNodesList;
use Phi\Exception\MissingNodeException;
use Phi\NodeConverter;
use Phi\Specification;
use Phi\Optional;
use Phi\Specifications\And_;
use Phi\Specifications\Any;
use Phi\Specifications\IsToken;
use Phi\Specifications\IsInstanceOf;
use Phi\Specifications\ValidCompoundNode;
use Phi\Specifications\EachItem;
use Phi\Specifications\EachSeparator;
use Phi\Nodes as Nodes;
use Phi\Specifications as Specs;

abstract class GeneratedInlineHtmlStatement extends CompoundNode implements Nodes\Statement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'closingTag' => new Optional(new IsToken(\T_CLOSE_TAG)),
                'content' => new Optional(new IsToken(\T_INLINE_HTML)),
                'openingTag' => new Optional(new IsToken(\T_OPEN_TAG)),
            ]),
        ];
    }

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
        parent::__construct();
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
     * @param Token|null $closingTag
     * @param Token|null $content
     * @param Token|null $openingTag
     * @return static
     */
    public static function __instantiateUnchecked($closingTag, $content, $openingTag)
    {
        $instance = new static();
        $instance->closingTag = $closingTag;
        $instance->content = $content;
        $instance->openingTag = $openingTag;
        return $instance;
    }

    public function &_getNodeRefs(): array
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
            $closingTag = NodeConverter::convert($closingTag, Token::class, $this->_phpVersion);
            $closingTag->_attachTo($this);
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
            $content = NodeConverter::convert($content, Token::class, $this->_phpVersion);
            $content->_attachTo($this);
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
            $openingTag = NodeConverter::convert($openingTag, Token::class, $this->_phpVersion);
            $openingTag->_attachTo($this);
        }
        if ($this->openingTag !== null)
        {
            $this->openingTag->detach();
        }
        $this->openingTag = $openingTag;
    }
}
