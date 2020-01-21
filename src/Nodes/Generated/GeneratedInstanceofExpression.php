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

abstract class GeneratedInstanceofExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'value' => new Specs\IsReadExpression,
                'operator' => new IsToken(\T_INSTANCEOF),
                'type' => new Specs\IsReadExpression,
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
    private $operator;
    /**
     * @var Nodes\Expression|null
     */
    private $type;

    /**
     * @param Nodes\Expression|Node|string|null $value
     * @param Nodes\Expression|Node|string|null $type
     */
    public function __construct($value = null, $type = null)
    {
        parent::__construct();
        if ($value !== null)
        {
            $this->setValue($value);
        }
        if ($type !== null)
        {
            $this->setType($type);
        }
    }

    /**
     * @param Nodes\Expression|null $value
     * @param Token|null $operator
     * @param Nodes\Expression|null $type
     * @return static
     */
    public static function __instantiateUnchecked($value, $operator, $type)
    {
        $instance = new static();
        $instance->value = $value;
        $instance->operator = $operator;
        $instance->type = $type;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'value' => &$this->value,
            'operator' => &$this->operator,
            'type' => &$this->type,
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

    public function getOperator(): Token
    {
        if ($this->operator === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator;
    }

    public function hasOperator(): bool
    {
        return $this->operator !== null;
    }

    /**
     * @param Token|Node|string|null $operator
     */
    public function setOperator($operator): void
    {
        if ($operator !== null)
        {
            /** @var Token $operator */
            $operator = NodeConverter::convert($operator, Token::class, $this->_phpVersion);
            $operator->_attachTo($this);
        }
        if ($this->operator !== null)
        {
            $this->operator->detach();
        }
        $this->operator = $operator;
    }

    public function getType(): Nodes\Expression
    {
        if ($this->type === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $type
     */
    public function setType($type): void
    {
        if ($type !== null)
        {
            /** @var Nodes\Expression $type */
            $type = NodeConverter::convert($type, Nodes\Expression::class, $this->_phpVersion);
            $type->_attachTo($this);
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }
}
