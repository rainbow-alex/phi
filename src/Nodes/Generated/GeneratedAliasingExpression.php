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

abstract class GeneratedAliasingExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'alias' => new Specs\IsAliasWriteExpression,
                'operator1' => new IsToken('='),
                'operator2' => new IsToken('&'),
                'value' => new Specs\IsAliasReadExpression,
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $alias;
    /**
     * @var Token|null
     */
    private $operator1;
    /**
     * @var Token|null
     */
    private $operator2;
    /**
     * @var Nodes\Expression|null
     */
    private $value;

    /**
     * @param Nodes\Expression|Node|string|null $alias
     * @param Nodes\Expression|Node|string|null $value
     */
    public function __construct($alias = null, $value = null)
    {
        parent::__construct();
        if ($alias !== null)
        {
            $this->setAlias($alias);
        }
        if ($value !== null)
        {
            $this->setValue($value);
        }
    }

    /**
     * @param Nodes\Expression|null $alias
     * @param Token|null $operator1
     * @param Token|null $operator2
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($alias, $operator1, $operator2, $value)
    {
        $instance = new static();
        $instance->alias = $alias;
        $instance->operator1 = $operator1;
        $instance->operator2 = $operator2;
        $instance->value = $value;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'alias' => &$this->alias,
            'operator1' => &$this->operator1,
            'operator2' => &$this->operator2,
            'value' => &$this->value,
        ];
        return $refs;
    }

    public function getAlias(): Nodes\Expression
    {
        if ($this->alias === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->alias;
    }

    public function hasAlias(): bool
    {
        return $this->alias !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $alias
     */
    public function setAlias($alias): void
    {
        if ($alias !== null)
        {
            /** @var Nodes\Expression $alias */
            $alias = NodeConverter::convert($alias, Nodes\Expression::class, $this->_phpVersion);
            $alias->_attachTo($this);
        }
        if ($this->alias !== null)
        {
            $this->alias->detach();
        }
        $this->alias = $alias;
    }

    public function getOperator1(): Token
    {
        if ($this->operator1 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator1;
    }

    public function hasOperator1(): bool
    {
        return $this->operator1 !== null;
    }

    /**
     * @param Token|Node|string|null $operator1
     */
    public function setOperator1($operator1): void
    {
        if ($operator1 !== null)
        {
            /** @var Token $operator1 */
            $operator1 = NodeConverter::convert($operator1, Token::class, $this->_phpVersion);
            $operator1->_attachTo($this);
        }
        if ($this->operator1 !== null)
        {
            $this->operator1->detach();
        }
        $this->operator1 = $operator1;
    }

    public function getOperator2(): Token
    {
        if ($this->operator2 === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->operator2;
    }

    public function hasOperator2(): bool
    {
        return $this->operator2 !== null;
    }

    /**
     * @param Token|Node|string|null $operator2
     */
    public function setOperator2($operator2): void
    {
        if ($operator2 !== null)
        {
            /** @var Token $operator2 */
            $operator2 = NodeConverter::convert($operator2, Token::class, $this->_phpVersion);
            $operator2->_attachTo($this);
        }
        if ($this->operator2 !== null)
        {
            $this->operator2->detach();
        }
        $this->operator2 = $operator2;
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
