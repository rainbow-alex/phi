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

abstract class GeneratedConstantInterpolatedStringPart extends CompoundNode implements Nodes\InterpolatedStringPart
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'content' => new IsToken(\T_ENCAPSED_AND_WHITESPACE),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $content;

    /**
     * @param Token|Node|string|null $content
     */
    public function __construct($content = null)
    {
        parent::__construct();
        if ($content !== null)
        {
            $this->setContent($content);
        }
    }

    /**
     * @param Token|null $content
     * @return static
     */
    public static function __instantiateUnchecked($content)
    {
        $instance = new static();
        $instance->content = $content;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'content' => &$this->content,
        ];
        return $refs;
    }

    public function getContent(): Token
    {
        if ($this->content === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
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
}
