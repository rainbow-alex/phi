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

abstract class GeneratedExtends extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'keyword' => new IsToken(\T_EXTENDS),
                'names' => new And_(new EachItem(new IsInstanceOf(Nodes\RegularName::class)), new EachSeparator(new IsToken(','))),
            ]),
        ];
    }

    /**
     * @var Token|null
     */
    private $keyword;
    /**
     * @var SeparatedNodesList|Nodes\RegularName[]
     */
    private $names;

    /**
     * @param Nodes\RegularName $name
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
     * @param mixed[] $names
     * @return static
     */
    public static function __instantiateUnchecked($keyword, $names)
    {
        $instance = new static();
        $instance->keyword = $keyword;
        $instance->names->__initUnchecked($names);
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'keyword' => &$this->keyword,
            'names' => &$this->names,
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

    /**
     * @return SeparatedNodesList|Nodes\RegularName[]
     */
    public function getNames(): SeparatedNodesList
    {
        return $this->names;
    }

    /**
     * @param Nodes\RegularName $name
     */
    public function addName($name): void
    {
        /** @var Nodes\RegularName $name */
        $name = NodeConverter::convert($name, Nodes\RegularName::class);
        $this->names->add($name);
    }
}
