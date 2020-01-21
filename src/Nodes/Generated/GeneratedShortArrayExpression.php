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

abstract class GeneratedShortArrayExpression extends CompoundNode implements Nodes\ArrayExpression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'leftBracket' => new IsToken('['),
                'items' => new And_(new EachItem(new IsInstanceOf(Nodes\ArrayItem::class)), new EachSeparator(new IsToken(','))),
                'rightBracket' => new IsToken(']'),
            ]),
        ];
    }

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
        parent::__construct();
        $this->items = new SeparatedNodesList();
        if ($item !== null)
        {
            $this->addItem($item);
        }
    }

    /**
     * @param Token|null $leftBracket
     * @param mixed[] $items
     * @param Token|null $rightBracket
     * @return static
     */
    public static function __instantiateUnchecked($leftBracket, $items, $rightBracket)
    {
        $instance = new static();
        $instance->leftBracket = $leftBracket;
        $instance->items->__initUnchecked($items);
        $instance->rightBracket = $rightBracket;
        return $instance;
    }

    public function &_getNodeRefs(): array
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
            $leftBracket = NodeConverter::convert($leftBracket, Token::class, $this->_phpVersion);
            $leftBracket->_attachTo($this);
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
        $item = NodeConverter::convert($item, Nodes\ArrayItem::class);
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
            $rightBracket = NodeConverter::convert($rightBracket, Token::class, $this->_phpVersion);
            $rightBracket->_attachTo($this);
        }
        if ($this->rightBracket !== null)
        {
            $this->rightBracket->detach();
        }
        $this->rightBracket = $rightBracket;
    }
}
