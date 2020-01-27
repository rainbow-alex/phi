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

abstract class GeneratedArrayAccessExpression extends Nodes\Expression
{
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
     * @param int $phpVersion
     * @param Nodes\Expression|null $accessee
     * @param Token|null $leftBracket
     * @param Nodes\Expression|null $index
     * @param Token|null $rightBracket
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $accessee, $leftBracket, $index, $rightBracket)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->accessee = $accessee;
        $instance->accessee->parent = $instance;
        $instance->leftBracket = $leftBracket;
        $instance->leftBracket->parent = $instance;
        $instance->index = $index;
        if ($index)
        {
            $instance->index->parent = $instance;
        }
        $instance->rightBracket = $rightBracket;
        $instance->rightBracket->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
            $accessee = NodeConverter::convert($accessee, Nodes\Expression::class, $this->phpVersion);
            $accessee->detach();
            $accessee->parent = $this;
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
            $index = NodeConverter::convert($index, Nodes\Expression::class, $this->phpVersion);
            $index->detach();
            $index->parent = $this;
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
            if ($this->accessee === null) throw ValidationException::childRequired($this, 'accessee');
            if ($this->leftBracket === null) throw ValidationException::childRequired($this, 'leftBracket');
            if ($this->rightBracket === null) throw ValidationException::childRequired($this, 'rightBracket');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->accessee->_validate($flags);
        if ($this->index)
        {
            $this->index->_validate($flags);
        }
    }
}
