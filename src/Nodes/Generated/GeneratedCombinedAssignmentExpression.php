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

abstract class GeneratedCombinedAssignmentExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'lvalue' => new And_(new Specs\IsReadExpression, new Specs\IsWriteExpression),
                'operator' => new IsToken(\T_PLUS_EQUAL, \T_MINUS_EQUAL, \T_MUL_EQUAL, \T_POW_EQUAL, \T_DIV_EQUAL, \T_CONCAT_EQUAL, \T_MOD_EQUAL, \T_AND_EQUAL, \T_OR_EQUAL, \T_XOR_EQUAL, \T_SL_EQUAL, \T_SR_EQUAL),
                'value' => new Specs\IsReadExpression,
            ]),
        ];
    }

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
        parent::__construct();
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
     * @param Nodes\Expression|null $lvalue
     * @param Token|null $operator
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($lvalue, $operator, $value)
    {
        $instance = new static();
        $instance->lvalue = $lvalue;
        $instance->operator = $operator;
        $instance->value = $value;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'lvalue' => &$this->lvalue,
            'operator' => &$this->operator,
            'value' => &$this->value,
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
            $lvalue = NodeConverter::convert($lvalue, Nodes\Expression::class, $this->_phpVersion);
            $lvalue->_attachTo($this);
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
            $operator = NodeConverter::convert($operator, Token::class, $this->_phpVersion);
            $operator->_attachTo($this);
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
            $value = NodeConverter::convert($value, Nodes\Expression::class, $this->_phpVersion);
            $value->_attachTo($this);
        }
        if ($this->value !== null)
        {
            $this->value->detach();
        }
        $this->value = $value;
    }
}
