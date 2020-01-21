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

abstract class GeneratedArrayAccessExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'accessee' => new Specs\IsReadExpression,
                'leftBracket' => new IsToken('['),
                'index' => new Optional(new Specs\IsReadExpression),
                'rightBracket' => new IsToken(']'),
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $accessee;
    /**
     * @var Token|null
     */
    private $leftBracket;
    /**
     * @var Nodes\Expression|null
     */
    private $index;
    /**
     * @var Token|null
     */
    private $rightBracket;

    /**
     * @param Nodes\Expression|Node|string|null $accessee
     * @param Nodes\Expression|Node|string|null $index
     */
    public function __construct($accessee = null, $index = null)
    {
        parent::__construct();
        if ($accessee !== null)
        {
            $this->setAccessee($accessee);
        }
        if ($index !== null)
        {
            $this->setIndex($index);
        }
    }

    /**
     * @param Nodes\Expression|null $accessee
     * @param Token|null $leftBracket
     * @param Nodes\Expression|null $index
     * @param Token|null $rightBracket
     * @return static
     */
    public static function __instantiateUnchecked($accessee, $leftBracket, $index, $rightBracket)
    {
        $instance = new static();
        $instance->accessee = $accessee;
        $instance->leftBracket = $leftBracket;
        $instance->index = $index;
        $instance->rightBracket = $rightBracket;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'accessee' => &$this->accessee,
            'leftBracket' => &$this->leftBracket,
            'index' => &$this->index,
            'rightBracket' => &$this->rightBracket,
        ];
        return $refs;
    }

    public function getAccessee(): Nodes\Expression
    {
        if ($this->accessee === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->accessee;
    }

    public function hasAccessee(): bool
    {
        return $this->accessee !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $accessee
     */
    public function setAccessee($accessee): void
    {
        if ($accessee !== null)
        {
            /** @var Nodes\Expression $accessee */
            $accessee = NodeConverter::convert($accessee, Nodes\Expression::class, $this->_phpVersion);
            $accessee->_attachTo($this);
        }
        if ($this->accessee !== null)
        {
            $this->accessee->detach();
        }
        $this->accessee = $accessee;
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

    public function getIndex(): ?Nodes\Expression
    {
        return $this->index;
    }

    public function hasIndex(): bool
    {
        return $this->index !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $index
     */
    public function setIndex($index): void
    {
        if ($index !== null)
        {
            /** @var Nodes\Expression $index */
            $index = NodeConverter::convert($index, Nodes\Expression::class, $this->_phpVersion);
            $index->_attachTo($this);
        }
        if ($this->index !== null)
        {
            $this->index->detach();
        }
        $this->index = $index;
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
