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

abstract class GeneratedNamedType extends Nodes\Type
{
    /**
     * @var Nodes\Name|null
     */
    private $name;

    /**
     * @param Nodes\Name|Node|string|null $name
     */
    public function __construct($name = null)
    {
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Name|null $name
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $name)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->name = $name;
        $instance->name->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "name" => &$this->name,
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

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->name === null) throw ValidationException::childRequired($this, "name");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->name->_validate($flags);
    }
}
