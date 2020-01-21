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

abstract class GeneratedUseName extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'name' => new Any,
                'alias' => new Optional(new Any),
            ]),
        ];
    }

    /**
     * @var Nodes\RegularName|null
     */
    private $name;
    /**
     * @var Nodes\UseAlias|null
     */
    private $alias;

    /**
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Nodes\RegularName|null $name
     * @param Nodes\UseAlias|null $alias
     * @return static
     */
    public static function __instantiateUnchecked($name, $alias)
    {
        $instance = new static();
        $instance->name = $name;
        $instance->alias = $alias;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'name' => &$this->name,
            'alias' => &$this->alias,
        ];
        return $refs;
    }

    public function getName(): Nodes\RegularName
    {
        if ($this->name === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->name;
    }

    public function hasName(): bool
    {
        return $this->name !== null;
    }

    /**
     * @param Nodes\RegularName|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Nodes\RegularName $name */
            $name = NodeConverter::convert($name, Nodes\RegularName::class, $this->_phpVersion);
            $name->_attachTo($this);
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }

    public function getAlias(): ?Nodes\UseAlias
    {
        return $this->alias;
    }

    public function hasAlias(): bool
    {
        return $this->alias !== null;
    }

    /**
     * @param Nodes\UseAlias|Node|string|null $alias
     */
    public function setAlias($alias): void
    {
        if ($alias !== null)
        {
            /** @var Nodes\UseAlias $alias */
            $alias = NodeConverter::convert($alias, Nodes\UseAlias::class, $this->_phpVersion);
            $alias->_attachTo($this);
        }
        if ($this->alias !== null)
        {
            $this->alias->detach();
        }
        $this->alias = $alias;
    }
}
