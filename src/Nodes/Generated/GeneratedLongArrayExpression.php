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

abstract class GeneratedLongArrayExpression extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $keyword;

    /**
     * @var Token|null
     */
    private $leftParenthesis;

    /**
     * @var SeparatedNodesList|Nodes\ArrayItem[]
     * @phpstan-var SeparatedNodesList<\Phi\Nodes\ArrayItem>
     */
    private $items;

    /**
     * @var Token|null
     */
    private $rightParenthesis;


    /**
     */
    public function __construct()
    {
        $this->items = new SeparatedNodesList();
    }

    /**
     * @param int $phpVersion
     * @param Token $keyword
     * @param Token $leftParenthesis
     * @param mixed[] $items
     * @param Token $rightParenthesis
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $keyword, $leftParenthesis, $items, $rightParenthesis)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->keyword = $keyword;
        $keyword->parent = $instance;
        $instance->leftParenthesis = $leftParenthesis;
        $leftParenthesis->parent = $instance;
        $instance->items->__initUnchecked($items);
        $instance->items->parent = $instance;
        $instance->rightParenthesis = $rightParenthesis;
        $rightParenthesis->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "keyword" => &$this->keyword,
            "leftParenthesis" => &$this->leftParenthesis,
            "items" => &$this->items,
            "rightParenthesis" => &$this->rightParenthesis,
        ];
        return $refs;
    }

    public function getKeyword(): Token
    {
        if ($this->keyword === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->keyword;
    }

    public function hasKeyword(): bool
    {
        return $this->keyword !== null;
    }

    /**
     * @param Token|Node|string|null $keyword
     */
    public function setKeyword($keyword): void
    {
        if ($keyword !== null)
        {
            /** @var Token $keyword */
            $keyword = NodeConverter::convert($keyword, Token::class, $this->phpVersion);
            $keyword->detach();
            $keyword->parent = $this;
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getLeftParenthesis(): Token
    {
        if ($this->leftParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftParenthesis;
    }

    public function hasLeftParenthesis(): bool
    {
        return $this->leftParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $leftParenthesis
     */
    public function setLeftParenthesis($leftParenthesis): void
    {
        if ($leftParenthesis !== null)
        {
            /** @var Token $leftParenthesis */
            $leftParenthesis = NodeConverter::convert($leftParenthesis, Token::class, $this->phpVersion);
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
     * @return SeparatedNodesList|Nodes\ArrayItem[]
     * @phpstan-return SeparatedNodesList<\Phi\Nodes\ArrayItem>
     */
    public function getItems(): SeparatedNodesList
    {
        return $this->items;
    }

    /**
     * @param Nodes\ArrayItem $item
     */
    public function addItem($item): void
    {
        /** @var Nodes\ArrayItem $item */
        $item = NodeConverter::convert($item, Nodes\ArrayItem::class, $this->phpVersion);
        $this->items->add($item);
    }

    public function getRightParenthesis(): Token
    {
        if ($this->rightParenthesis === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightParenthesis;
    }

    public function hasRightParenthesis(): bool
    {
        return $this->rightParenthesis !== null;
    }

    /**
     * @param Token|Node|string|null $rightParenthesis
     */
    public function setRightParenthesis($rightParenthesis): void
    {
        if ($rightParenthesis !== null)
        {
            /** @var Token $rightParenthesis */
            $rightParenthesis = NodeConverter::convert($rightParenthesis, Token::class, $this->phpVersion);
            $rightParenthesis->detach();
            $rightParenthesis->parent = $this;
        }
        if ($this->rightParenthesis !== null)
        {
            $this->rightParenthesis->detach();
        }
        $this->rightParenthesis = $rightParenthesis;
    }

    protected function _validate(int $flags): void
    {
        if ($this->keyword === null) throw ValidationException::childRequired($this, "keyword");
        if ($this->leftParenthesis === null) throw ValidationException::childRequired($this, "leftParenthesis");
        if ($this->rightParenthesis === null) throw ValidationException::childRequired($this, "rightParenthesis");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->items->_validate($flags);
    }
}
