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

abstract class GeneratedUseName extends CompoundNode
{
    /**
     * @var Nodes\Name|null
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
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Name $name
     * @param Nodes\UseAlias|null $alias
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $name, $alias)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->name = $name;
        $name->parent = $instance;
        $instance->alias = $alias;
        if ($alias) $alias->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "name" => &$this->name,
            "alias" => &$this->alias,
        ];
        return $refs;
    }

    public function getName(): Nodes\Name
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
     * @param Nodes\Name|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Nodes\Name $name */
            $name = NodeConverter::convert($name, Nodes\Name::class, $this->phpVersion);
            $name->detach();
            $name->parent = $this;
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
            $alias = NodeConverter::convert($alias, Nodes\UseAlias::class, $this->phpVersion);
            $alias->detach();
            $alias->parent = $this;
        }
        if ($this->alias !== null)
        {
            $this->alias->detach();
        }
        $this->alias = $alias;
    }

    protected function _validate(int $flags): void
    {
        if ($this->name === null) throw ValidationException::childRequired($this, "name");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->name->_validate($flags);
        if ($this->alias)
        {
            $this->alias->_validate($flags);
        }
    }
}
