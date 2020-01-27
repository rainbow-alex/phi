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

abstract class GeneratedAliasingExpression extends Nodes\Expression
{
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
     * @param int $phpVersion
     * @param Nodes\Expression|null $alias
     * @param Token|null $operator1
     * @param Token|null $operator2
     * @param Nodes\Expression|null $value
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $alias, $operator1, $operator2, $value)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->alias = $alias;
        $instance->alias->parent = $instance;
        $instance->operator1 = $operator1;
        $instance->operator1->parent = $instance;
        $instance->operator2 = $operator2;
        $instance->operator2->parent = $instance;
        $instance->value = $value;
        $instance->value->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
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
            $alias = NodeConverter::convert($alias, Nodes\Expression::class, $this->phpVersion);
            $alias->detach();
            $alias->parent = $this;
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
            $operator1 = NodeConverter::convert($operator1, Token::class, $this->phpVersion);
            $operator1->detach();
            $operator1->parent = $this;
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
            $operator2 = NodeConverter::convert($operator2, Token::class, $this->phpVersion);
            $operator2->detach();
            $operator2->parent = $this;
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
            if ($this->alias === null) throw ValidationException::childRequired($this, 'alias');
            if ($this->operator1 === null) throw ValidationException::childRequired($this, 'operator1');
            if ($this->operator2 === null) throw ValidationException::childRequired($this, 'operator2');
            if ($this->value === null) throw ValidationException::childRequired($this, 'value');
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->alias->_validate($flags);
        $this->value->_validate($flags);
    }
}
