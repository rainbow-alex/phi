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

abstract class GeneratedGroupedUseStatement extends CompoundNode implements Nodes\UseStatement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_USE),
                'type' => new Optional(new IsToken(\T_FUNCTION, \T_CONST)),
                'prefix' => new Optional(new Any),
                'leftBrace' => new IsToken('{'),
                'uses' => new And_(new EachItem(new IsInstanceOf(Nodes\UseName::class)), new EachSeparator(new IsToken(','))),
                'rightBrace' => new IsToken('}'),
                'semiColon' => new Optional(new IsToken(';')),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var Token|null
     */
    private $type;
    /**
     * @var Nodes\GroupedUsePrefix|null
     */
    private $prefix;
    /**
     * @var Token|null
     */
    private $leftBrace;
    /**
     * @var SeparatedNodesList|Nodes\UseName[]
     */
    private $uses;
    /**
     * @var Token|null
     */
    private $rightBrace;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $this->uses = new SeparatedNodesList();
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $type
     * @param Nodes\GroupedUsePrefix|null $prefix
     * @param Token|null $leftBrace
     * @param mixed[] $uses
     * @param Token|null $rightBrace
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $type, $prefix, $leftBrace, $uses, $rightBrace, $semiColon)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->type = $type;
        $instance->prefix = $prefix;
        $instance->leftBrace = $leftBrace;
        $instance->uses->__initUnchecked($uses);
        $instance->rightBrace = $rightBrace;
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'type' => &$this->type,
            'prefix' => &$this->prefix,
            'leftBrace' => &$this->leftBrace,
            'uses' => &$this->uses,
            'rightBrace' => &$this->rightBrace,
            'semiColon' => &$this->semiColon,
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
            $keyword = NodeConverter::convert($keyword, Token::class, $this->_phpVersion);
            $keyword->_attachTo($this);
        }
        if ($this->keyword !== null)
        {
            $this->keyword->detach();
        }
        $this->keyword = $keyword;
    }

    public function getType(): ?Token
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param Token|Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var Token $type */
            $type = NodeConverter::convert($type, Token::class, $this->_phpVersion);
            $type->_attachTo($this);
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    public function getPrefix(): ?Nodes\GroupedUsePrefix
    {
        return $this->prefix;
    }

    public function hasPrefix(): bool
    {
        return $this->prefix !== null;
    }

    /**
     * @param Nodes\GroupedUsePrefix|Node|string|null $prefix
     */
    public function setPrefix($prefix): void
    {
        if ($prefix !== null)
        {
            /** @var Nodes\GroupedUsePrefix $prefix */
            $prefix = NodeConverter::convert($prefix, Nodes\GroupedUsePrefix::class, $this->_phpVersion);
            $prefix->_attachTo($this);
        }
        if ($this->prefix !== null)
        {
            $this->prefix->detach();
        }
        $this->prefix = $prefix;
    }

    public function getLeftBrace(): Token
    {
        if ($this->leftBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->leftBrace;
    }

    public function hasLeftBrace(): bool
    {
        return $this->leftBrace !== null;
    }

    /**
     * @param Token|Node|string|null $leftBrace
     */
    public function setLeftBrace($leftBrace): void
    {
        if ($leftBrace !== null)
        {
            /** @var Token $leftBrace */
            $leftBrace = NodeConverter::convert($leftBrace, Token::class, $this->_phpVersion);
            $leftBrace->_attachTo($this);
        }
        if ($this->leftBrace !== null)
        {
            $this->leftBrace->detach();
        }
        $this->leftBrace = $leftBrace;
    }

    /**
     * @return SeparatedNodesList|Nodes\UseName[]
     */
    public function getUses(): SeparatedNodesList
    {
        return $this->uses;
    }

    /**
     * @param Nodes\UseName $us
     */
    public function addUs($us): void
    {
        /** @var Nodes\UseName $us */
        $us = NodeConverter::convert($us, Nodes\UseName::class);
        $this->uses->add($us);
    }

    public function getRightBrace(): Token
    {
        if ($this->rightBrace === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->rightBrace;
    }

    public function hasRightBrace(): bool
    {
        return $this->rightBrace !== null;
    }

    /**
     * @param Token|Node|string|null $rightBrace
     */
    public function setRightBrace($rightBrace): void
    {
        if ($rightBrace !== null)
        {
            /** @var Token $rightBrace */
            $rightBrace = NodeConverter::convert($rightBrace, Token::class, $this->_phpVersion);
            $rightBrace->_attachTo($this);
        }
        if ($this->rightBrace !== null)
        {
            $this->rightBrace->detach();
        }
        $this->rightBrace = $rightBrace;
    }

    public function getSemiColon(): ?Token
    {
        return $this->semiColon;
    }

    public function hasSemiColon(): bool
    {
        return $this->semiColon !== null;
    }

    /**
     * @param Token|Node|string|null $semiColon
     */
    public function setSemiColon($semiColon): void
    {
        if ($semiColon !== null)
        {
            /** @var Token $semiColon */
            $semiColon = NodeConverter::convert($semiColon, Token::class, $this->_phpVersion);
            $semiColon->_attachTo($this);
        }
        if ($this->semiColon !== null)
        {
            $this->semiColon->detach();
        }
        $this->semiColon = $semiColon;
    }
}