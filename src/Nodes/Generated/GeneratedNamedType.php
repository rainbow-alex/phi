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

abstract class GeneratedNamedType extends CompoundNode implements Nodes\Type
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'name' => new Any,
            ]),
        ];
    }

    /**
     * @var Nodes\Name|null
     */
    private $name;

    /**
     * @param Nodes\Name|Node|string|null $name
     */
    public function __construct($name = null)
    {
        parent::__construct();
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param Nodes\Name|null $name
     * @return static
     */
    public static function __instantiateUnchecked($name)
    {
        $instance = new static();
        $instance->name = $name;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'name' => &$this->name,
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
            $name = NodeConverter::convert($name, Nodes\Name::class, $this->_phpVersion);
            $name->_attachTo($this);
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }
}
