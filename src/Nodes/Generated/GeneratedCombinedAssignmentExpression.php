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

abstract class GeneratedCombinedAssignmentExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $lvalue;

    /**
     * @var Token|null
     */
    private $operator;

    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     * @param Nodes\Expression|Node|string|null $lvalue
     * @param Nodes\Expression|Node|string|null $value
     */
    public function __construct($lvalue = null, $value = null)
    {
        if ($lvalue !== null)
        {
            $this->setLvalue($lvalue);
        }
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression|null $lvalue
     * @param Token|null $operator
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $lvalue, $operator, $value)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->lvalue = $lvalue;
        $instance->lvalue->parent = $instance;
        $instance->operator = $operator;
        $instance->operator->parent = $instance;
        $instance->value = $value;
        $instance->value->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "lvalue" => &$this->lvalue,
            "operator" => &$this->operator,
            "value" => &$this->value,
        ];
        return $refs;
    }

    public function getLvalue(): Nodes\Expression
    {
        if ($this->lvalue === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->lvalue;
    }

    public function hasLvalue(): bool
    {
        return $this->lvalue !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $lvalue
     */
    public function setLvalue($lvalue): void
    {
        if ($lvalue !== null)
        {
            /** @var Nodes\Expression $lvalue */
            $lvalue = NodeConverter::convert($lvalue, Nodes\Expression::class, $this->phpVersion);
            $lvalue->detach();
            $lvalue->parent = $this;
        }
        if ($this->lvalue !== null)
        {
            $this->lvalue->detach();
        }
        $this->lvalue = $lvalue;
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

    protected function _validate(int $flags): void
    {
        if ($flags & self::VALIDATE_TYPES)
        {
            if ($this->lvalue === null) throw ValidationException::childRequired($this, "lvalue");
            if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
            if ($this->value === null) throw ValidationException::childRequired($this, "value");
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->lvalue->_validate($flags);
        $this->value->_validate($flags);
    }
}
