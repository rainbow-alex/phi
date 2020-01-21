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

abstract class GeneratedRegularUseStatement extends CompoundNode implements Nodes\UseStatement
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_USE),
                'type' => new Optional(new IsToken(\T_FUNCTION, \T_CONST)),
                'names' => new And_(new EachItem(new IsInstanceOf(Nodes\UseName::class)), new EachSeparator(new IsToken(','))),
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
     * @var SeparatedNodesList|Nodes\UseName[]
     */
    private $names;
    /**
     * @var Token|null
     */
    private $semiColon;

    /**
     * @param Nodes\UseName $name
     */
    public function __construct($name = null)
    {
        parent::__construct();
        $this->names = new SeparatedNodesList();
        if ($name !== null)
        {
            $this->addName($name);
        }
    }

    /**
     * @param Token|null $keyword
     * @param Token|null $type
     * @param mixed[] $names
     * @param Token|null $semiColon
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $type, $names, $semiColon)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->type = $type;
        $instance->names->__initUnchecked($names);
        $instance->semiColon = $semiColon;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'type' => &$this->type,
            'names' => &$this->names,
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

    /**
     * @return SeparatedNodesList|Nodes\UseName[]
     */
    public function getNames(): SeparatedNodesList
    {
        return $this->names;
    }

    /**
     * @param Nodes\UseName $name
     */
    public function addName($name): void
    {
        /** @var Nodes\UseName $name */
        $name = NodeConverter::convert($name, Nodes\UseName::class);
        $this->names->add($name);
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
