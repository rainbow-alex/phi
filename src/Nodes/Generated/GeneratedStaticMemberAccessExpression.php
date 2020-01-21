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

abstract class GeneratedStaticMemberAccessExpression extends CompoundNode implements Nodes\Expression
{
    /** @var Specification[] */
    private static $specifications;
    protected static function getSpecifications(): array
    {
        return self::$specifications ?? self::$specifications = [
            new ValidCompoundNode([
                'accessee' => new Specs\IsReadExpression,
                'operator' => new IsToken(\T_DOUBLE_COLON),
                'name' => new IsToken(\T_STRING),
            ]),
        ];
    }

    /**
     * @var Nodes\Expression|null
     */
    private $accessee;
    /**
     * @var Token|null
     */
    private $operator;
    /**
     * @var Token|null
     */
    private $name;

    /**
     * @param Nodes\Expression|Node|string|null $accessee
     * @param Token|Node|string|null $name
     */
    public function __construct($accessee = null, $name = null)
    {
        parent::__construct();
        if ($accessee !== null)
        {
            $this->setAccessee($accessee);
        }
        if ($name !== null)
        {
            $this->setName($name);
        }
    }

    /**
     * @param Nodes\Expression|null $accessee
     * @param Token|null $operator
     * @param Token|null $name
     * @return static
     */
    public static function __instantiateUnchecked($accessee, $operator, $name)
    {
        $instance = new static();
        $instance->accessee = $accessee;
        $instance->operator = $operator;
        $instance->name = $name;
        return $instance;
    }

    public function &_getNodeRefs(): array
    {
        $refs = [
            'accessee' => &$this->accessee,
            'operator' => &$this->operator,
            'name' => &$this->name,
        ];
        return $refs;
    }

    public function getAccessee(): Nodes\Expression
    {
        if ($this->accessee === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->accessee;
    }

    public function hasAccessee(): bool
    {
        return $this->accessee !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $accessee
     */
    public function setAccessee($accessee): void
    {
        if ($accessee !== null)
        {
            /** @var Nodes\Expression $accessee */
            $accessee = NodeConverter::convert($accessee, Nodes\Expression::class, $this->_phpVersion);
            $accessee->_attachTo($this);
        }
        if ($this->accessee !== null)
        {
            $this->accessee->detach();
        }
        $this->accessee = $accessee;
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

    public function getName(): Token
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
     * @param Token|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Token $name */
            $name = NodeConverter::convert($name, Token::class, $this->_phpVersion);
            $name->_attachTo($this);
        }
        if ($this->name !== null)
        {
            $this->name->detach();
        }
        $this->name = $name;
    }
}
