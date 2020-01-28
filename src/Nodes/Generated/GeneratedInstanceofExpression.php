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

abstract class GeneratedInstanceofExpression extends Nodes\Expression
{
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
     * @param int $phpVersion
     * @param Nodes\Expression $value
     * @param Token $operator
     * @param Nodes\Expression $type
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $value, $operator, $type)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->value = $value;
        $value->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->type = $type;
        $type->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "value" => &$this->value,
            "operator" => &$this->operator,
            "type" => &$this->type,
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->phpVersion);
            $value->detach();
            $value->parent = $this;
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
            $operator = NodeConverter::convert($operator, Token::class, $this->phpVersion);
            $operator->detach();
            $operator->parent = $this;
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
            $type = NodeConverter::convert($type, Nodes\Expression::class, $this->phpVersion);
            $type->detach();
            $type->parent = $this;
        }
        if ($this->type !== null)
        {
            $this->type->detach();
        }
        $this->type = $type;
    }

    protected function _validate(int $flags): void
    {
        if ($this->value === null) throw ValidationException::childRequired($this, "value");
        if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
        if ($this->type === null) throw ValidationException::childRequired($this, "type");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->value->_validate($flags);
        $this->type->_validate($flags);
    }
}
