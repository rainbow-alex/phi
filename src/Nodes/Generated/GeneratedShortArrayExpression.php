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

abstract class GeneratedShortArrayExpression extends Nodes\Expression
{
    /**
     * @var Token|null
     */
    private $leftBracket;

    /**
     * @var SeparatedNodesList|Nodes\ArrayItem[]
     */
    private $items;

    /**
     * @var Token|null
     */
    private $rightBracket;

    /**
     * @param Nodes\ArrayItem $item
     */
    public function __construct($item = null)
    {
        $this->items = new SeparatedNodesList();
        if ($item !== null)
        {
            $this->addItem($item);
        }
    }

    /**
     * @param int $phpVersion
     * @param Token|null $leftBracket
     * @param mixed[] $items
     * @param Token|null $rightBracket
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $leftBracket, $items, $rightBracket)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->leftBracket = $leftBracket;
        $instance->leftBracket->parent = $instance;
        $instance->items->__initUnchecked($items);
        $instance->items->parent = $instance;
        $instance->rightBracket = $rightBracket;
        $instance->rightBracket->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            'leftBracket' => &$this->leftBracket,
            'items' => &$this->items,
            'rightBracket' => &$this->rightBracket,
        ];
        return $refs;
    }

    public function getLeftBracket(): Token
    {
        if ($this->leftBracket === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftBracket;
    }

    public function hasLeftBracket(): bool
    {
        return $this->leftBracket !== null;
    }

    /**
     * @param Token|Node|string|null $leftBracket
     */
    public function setLeftBracket($leftBracket): void
    {
        if ($leftBracket !== null)
        {
            /** @var Token $leftBracket */
            $leftBracket = NodeConverter::convert($leftBracket, Token::class, $this->phpVersion);
            $leftBracket->detach();
            $leftBracket->parent = $this;
        }
        if ($this->leftBracket !== null)
        {
            $this->leftBracket->detach();
        }
        $this->leftBracket = $leftBracket;
    }

    /**
     * @return SeparatedNodesList|Nodes\ArrayItem[]
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

    public function getRightBracket(): Token
    {
        if ($this->rightBracket === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightBracket;
    }

    public function hasRightBracket(): bool
    {
        return $this->rightBracket !== null;
    }

    /**
     * @param Token|Node|string|null $rightBracket
     */
    public function setRightBracket($rightBracket): void
    {
        if ($rightBracket !== null)
        {
            /** @var Token $rightBracket */
            $rightBracket = NodeConverter::convert($rightBracket, Token::class, $this->phpVersion);
            $rightBracket->detach();
            $rightBracket->parent = $this;
        }
        if ($this->rightBracket !== null)
        {
            $this->rightBracket->detach();
        }
        $this->rightBracket = $rightBracket;
    }

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->leftBracket === null) throw ValidationException::childRequired($this, 'leftBracket');
            if ($this->rightBracket === null) throw ValidationException::childRequired($this, 'rightBracket');
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
