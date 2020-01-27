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

abstract class GeneratedConstantInterpolatedStringPart extends Nodes\CInterpolatedStringPart
{
    /**
     * @var Token|null
     */
    private $content;

    /**
     * @param Token|Node|string|null $content
     */
    public function __construct($content = null)
    {
        if ($content !== null)
        {
            $this->setContent($content);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $content
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $content)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->content = $content;
        $instance->content->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->content === null) throw ValidationException::childRequired($this, 'content');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
    }
}
