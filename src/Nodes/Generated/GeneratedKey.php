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

abstract class GeneratedKey extends CompoundNode
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'value' => new Any,
                'arrow' => new IsToken(\T_DOUBLE_ARROW),
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $value;
    /**
     * @var Token|null
     */
    private $arrow;

    /**
     * @param Nodes\Expression|Node|string|null $value
     */
    public function __construct($value = null)
    {
        parent::__construct();
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param Nodes\Expression|null $value
     * @param Token|null $arrow
     * @return static
     */
    public static function __instantiateUnchecked($value, $arrow)
    {
        $instance = new static();
        $instance->value = $value;
        $instance->arrow = $arrow;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'value' => &$this->value,
            'arrow' => &$this->arrow,
        ];
        return $refs;
    }

    public function getValue(): Nodes\Expression
    {
        if ($this->value === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->value;
    }

    public function hasValue(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $value
     */
    public function setValue($value): void
    {
        if ($value !== null)
        {
            /** @var Nodes\Expression $value */
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->_phpVersion);
            $value->_attachTo($this);
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }

    public function getArrow(): Token
    {
        if ($this->arrow === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->arrow;
    }

    public function hasArrow(): bool
    {
        return $this->arrow !== null;
    }

    /**
     * @param Token|Node|string|null $arrow
     */
    public function setArrow($arrow): void
    {
        if ($arrow !== null)
        {
            /** @var Token $arrow */
            $arrow = NodeConverter::convert($arrow, Token::class, $this->_phpVersion);
            $arrow->_attachTo($this);
        }
        if ($this->arrow !== null)
        {
            $this->arrow->detach();
        }
        $this->arrow = $arrow;
    }
}
