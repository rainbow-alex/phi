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

abstract class GeneratedPostIncrementExpression extends Nodes\CrementExpression
{
    /**
     * @var Nodes\Expression|null
     */
    private $expression;

    /**
     * @var Token|null
     */
    private $operator;


    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function __construct($expression = null)
    {
        if ($expression !== null)
        {
            $this->setExpression($expression);
        }
    }

    /**
     * @param int $phpVersion
     * @param Nodes\Expression $expression
     * @param Token $operator
     * @return static
     */
    public static function __instantiateUnchecked($phpVersion, $expression, $operator)
    {
        $instance = new static;
        $instance->phpVersion = $phpVersion;
        $instance->expression = $expression;
        $expression->parent = $instance;
        $instance->operator = $operator;
        $operator->parent = $instance;
        return $instance;
    }

    protected function &_getNodeRefs(): array
    {
        $refs = [
            "expression" => &$this->expression,
            "operator" => &$this->operator,
        ];
        return $refs;
    }

    public function getExpression(): Nodes\Expression
    {
        if ($this->expression === null)
        {
            throw new MissingNodeException($this, __FUNCTION__);
        }
        return $this->expression;
    }

    public function hasExpression(): bool
    {
        return $this->expression !== null;
    }

    /**
     * @param Nodes\Expression|Node|string|null $expression
     */
    public function setExpression($expression): void
    {
        if ($expression !== null)
        {
            /** @var Nodes\Expression $expression */
            $expression = NodeConverter::convert($expression, Nodes\Expression::class, $this->phpVersion);
            $expression->detach();
            $expression->parent = $this;
        }
        if ($this->expression !== null)
        {
            $this->expression->detach();
        }
        $this->expression = $expression;
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

    protected function _validate(int $flags): void
    {
        if ($this->expression === null) throw ValidationException::childRequired($this, "expression");
        if ($this->operator === null) throw ValidationException::childRequired($this, "operator");
        if ($flags & self::VALIDATE_TYPES)
        {
        }
        if ($flags & self::VALIDATE_EXPRESSION_CONTEXT)
        {
        }
        if ($flags & self::VALIDATE_TOKENS)
        {
        }
        $this->expression->_validate($flags);
    }
}
