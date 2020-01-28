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

abstract class GeneratedStaticPropertyAccessExpression extends Nodes\Expression
{
    /**
     * @var Nodes\Expression|null
     */
    private $accessee;

    /**
     * @var Token|null
     */
    private $operator;

    /**
     * @var Nodes\Variable|null
     */
    private $name;


    /**
     * @param Nodes\Expression|Node|string|null $accessee
     * @param Nodes\Variable|Node|string|null $name
     */
    public function __construct($accessee = null, $name = null)
    {
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
     * @param int $phpVersion
     * @param Nodes\Expression $accessee
     * @param Token $operator
     * @param Nodes\Variable $name
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $accessee, $operator, $name)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->accessee = $accessee;
        $accessee->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        $instance->name = $name;
        $name->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "accessee" => &$this->accessee,
            "operator" => &$this->operator,
            "name" => &$this->name,
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
            $accessee = NodeConverter::convert($accessee, Nodes\Expression::class, $this->phpVersion);
            $accessee->detach();
            $accessee->parent = $this;
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

    public function getName(): Nodes\Variable
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
     * @param Nodes\Variable|Node|string|null $name
     */
    public function setName($name): void
    {
        if ($name !== null)
        {
            /** @var Nodes\Variable $name */
            $name = NodeConverter::convert($name, Nodes\Variable::class, $this->phpVersion);
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
        if ($this->accessee === null) throw ValidationException::childRequired($this, "accessee");
        if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
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
        $this->accessee->_validate($flags);
        $this->name->_validate($flags);
    }
}
